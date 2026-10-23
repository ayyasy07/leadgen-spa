<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Models\TreatmentPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Treatment::with('prices');

        // Filter kategori (string)
        $category = $request->get('category', 'all');
        if ($category !== 'all') {
            if ($category === 'uncategorized') {
                $query->where(function ($q) {
                    $q->whereNull('category')->orWhere('category', '');
                });
            } else {
                $query->where('category', $category);
            }
        }

        // Filter featured
        $filter = $request->get('featured') == '1' ? 'featured' : 'all';
        if ($filter === 'featured') {
            $query->where('is_featured', 1);
        }

        $treatments = $query->orderBy('name')->get();

        // ===== KATEGORI DINAMIS (dari DB) =====
        $categories = Treatment::query()
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Count per kategori (global, bukan hasil filter)
        $countsPerCategory = Treatment::query()
            ->selectRaw('category, COUNT(*) as total')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->pluck('total', 'category');

        $uncategorizedCount = Treatment::where(function ($q) {
            $q->whereNull('category')->orWhere('category', '');
        })->count();

        $countsAll = Treatment::count();

        // Featured count (di kategori yang lagi dipilih)
        $featuredQuery = Treatment::where('is_featured', 1);
        if ($category !== 'all') {
            if ($category === 'uncategorized') {
                $featuredQuery->where(function ($q) {
                    $q->whereNull('category')->orWhere('category', '');
                });
            } else {
                $featuredQuery->where('category', $category);
            }
        }
        $featuredCurrentCategory = $featuredQuery->count();

        // Label kategori yang lagi aktif
        $categoryLabel = 'Category';
        if ($category === 'uncategorized') {
            $categoryLabel = 'No Category';
        } elseif ($category !== 'all') {
            $categoryLabel = $category;
        }

        return view('pages.treatments-crud.index', [
            'treatments'              => $treatments,
            'categories'              => $categories,
            'countsPerCategory'       => $countsPerCategory,
            'uncategorizedCount'      => $uncategorizedCount,
            'countsAll'               => $countsAll,
            'featuredCurrentCategory' => $featuredCurrentCategory,
            'category'                => $category,
            'categoryLabel'           => $categoryLabel,
            'filter'                  => $filter,
            'layout'                  => 'side-menu',
        ]);
    }

    public function create()
    {
        return view('pages.treatments-crud.create', [
            'layout' => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:treatments,slug',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'category'         => 'required|string|max:255',
            'unit'             => 'required|in:person,couple',
            'description'      => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',

            'image'            => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_2'          => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_3'          => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',

            'durations'        => 'required|array|min:1',
            'durations.*'      => 'required|integer|min:1',
            'prices'           => 'required|array|min:1',
            'prices.*'         => 'required|integer|min:0',
        ], [
            'image.required'   => 'Main image is required.',
            'image_2.required' => 'Image 2 is required.',
            'image_3.required' => 'Image 3 is required.',
        ]);

        $data = $request->only([
            'name', 'slug', 'meta_title', 'meta_description',
            'category', 'unit', 'description',
        ]);

        $data['is_active']   = $request->boolean('is_active') ? 1 : 0;
        $data['is_featured'] = $request->boolean('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('treatments', 'public');
        }
        if ($request->hasFile('image_2')) {
            $data['image_2'] = $request->file('image_2')->store('treatments', 'public');
        }
        if ($request->hasFile('image_3')) {
            $data['image_3'] = $request->file('image_3')->store('treatments', 'public');
        }

        DB::transaction(function () use ($data, $request) {
            // Kalau admin centang featured → unset featured lain di kategori
            if ($data['is_featured'] == 1 && !empty($data['category'])) {
                Treatment::where('category', $data['category'])
                    ->where('is_featured', 1)
                    ->update(['is_featured' => 0]);
            }

            $treatment = Treatment::create($data);

            // AUTO-FEATURED (cuma di store):
            // Kalo admin ga centang featured & treatment aktif,
            // dan kategori ini belum ada featured aktif → jadi featured
            if ($data['is_featured'] == 0 && $data['is_active'] == 1) {
                $hasFeatured = Treatment::where('category', $data['category'])
                    ->where('is_featured', 1)
                    ->where('is_active', 1)
                    ->exists();

                if (!$hasFeatured) {
                    $treatment->update(['is_featured' => 1]);
                }
            }

            foreach ($request->durations as $i => $duration) {
                TreatmentPrice::create([
                    'treatment_id' => $treatment->id,
                    'duration'     => $duration,
                    'price'        => $request->prices[$i],
                ]);
            }
        });

        // FIFO FALLBACK — pastiin kategori punya featured (kalau ada treatment aktif)
        $this->enforceFeaturedPerCategory($data['category']);

        return redirect()
            ->route('admin.treatments.index')
            ->with('success', 'Treatment created.');
    }

    public function edit(Treatment $treatment)
    {
        $treatment->load('prices');

        return view('pages.treatments-crud.edit', [
            'treatment' => $treatment,
            'layout'    => 'side-menu',
        ]);
    }

    public function update(Request $request, Treatment $treatment)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => [
                'nullable', 'string', 'max:255',
                Rule::unique('treatments', 'slug')->ignore($treatment->id),
            ],
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'category'         => 'required|string|max:255',
            'unit'             => 'required|in:person,couple',
            'description'      => 'nullable|string',
            'is_active'        => 'nullable|boolean',
            'is_featured'      => 'nullable|boolean',

            'image'            => ($treatment->image   ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_2'          => ($treatment->image_2 ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_3'          => ($treatment->image_3 ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp|max:4096',

            'durations'        => 'required|array|min:1',
            'durations.*'      => 'required|integer|min:1',
            'prices'           => 'required|array|min:1',
            'prices.*'         => 'required|integer|min:0',
        ], [
            'image.required'   => 'Main image is required.',
            'image_2.required' => 'Image 2 is required.',
            'image_3.required' => 'Image 3 is required.',
        ]);

        $data = $request->only([
            'name', 'slug', 'meta_title', 'meta_description',
            'category', 'unit', 'description',
        ]);

        $data['is_active']   = $request->boolean('is_active') ? 1 : 0;
        $data['is_featured'] = $request->boolean('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($treatment->image && Storage::disk('public')->exists($treatment->image)) {
                Storage::disk('public')->delete($treatment->image);
            }
            $data['image'] = $request->file('image')->store('treatments', 'public');
        }
        if ($request->hasFile('image_2')) {
            if ($treatment->image_2 && Storage::disk('public')->exists($treatment->image_2)) {
                Storage::disk('public')->delete($treatment->image_2);
            }
            $data['image_2'] = $request->file('image_2')->store('treatments', 'public');
        }
        if ($request->hasFile('image_3')) {
            if ($treatment->image_3 && Storage::disk('public')->exists($treatment->image_3)) {
                Storage::disk('public')->delete($treatment->image_3);
            }
            $data['image_3'] = $request->file('image_3')->store('treatments', 'public');
        }

        DB::transaction(function () use ($data, $request, $treatment) {
            // Kalau admin centang featured → unset featured lain di kategori
            if ($data['is_featured'] == 1 && !empty($data['category'])) {
                Treatment::where('category', $data['category'])
                    ->where('id', '!=', $treatment->id)
                    ->where('is_featured', 1)
                    ->update(['is_featured' => 0]);
            }

            $treatment->update($data);

            $treatment->prices()->delete();
            foreach ($request->durations as $i => $duration) {
                TreatmentPrice::create([
                    'treatment_id' => $treatment->id,
                    'duration'     => $duration,
                    'price'        => $request->prices[$i],
                ]);
            }
        });

        // FIFO FALLBACK — kalo kategori ini jadi kosong featured,
        // pilih pengganti dari treatment aktif lain (exclude yang barusan di-update)
        $this->enforceFeaturedPerCategory($data['category'], $treatment->id);

        return redirect()
            ->route('admin.treatments.index')
            ->with('success', 'Treatment updated.');
    }

    public function destroy(Treatment $treatment)
    {
        $category = $treatment->category;

        foreach (['image', 'image_2', 'image_3'] as $field) {
            if ($treatment->$field && Storage::disk('public')->exists($treatment->$field)) {
                Storage::disk('public')->delete($treatment->$field);
            }
        }

        $treatment->prices()->delete();
        $treatment->delete();

        // FIFO FALLBACK
        $this->enforceFeaturedPerCategory($category);

        return back()->with('success', 'Treatment deleted.');
    }

    /* ============================================
       FIX 3 — FIFO FALLBACK FEATURED PER KATEGORI

       Kalo kategori ini GA punya featured aktif,
       otomatis pilih treatment aktif paling lama (FIFO by created_at).

       $excludeId → treatment yang barusan di-update (biar ga auto-featured balik).
    ============================================ */
    private function enforceFeaturedPerCategory(string $category, ?int $excludeId = null): void
    {
        if (empty($category)) return;

        // Cek apakah masih ada featured aktif di kategori ini
        $hasFeatured = Treatment::where('category', $category)
            ->where('is_featured', 1)
            ->where('is_active', 1)
            ->exists();

        if ($hasFeatured) return;

        // FIFO: ambil treatment aktif paling lama
        $query = Treatment::where('category', $category)
            ->where('is_active', 1)
            ->orderBy('created_at');

        // Exclude treatment yang barusan di-update
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $candidate = $query->first();

        if ($candidate) {
            $candidate->update(['is_featured' => 1]);
        }
    }
}