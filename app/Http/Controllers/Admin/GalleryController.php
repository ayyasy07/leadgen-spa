<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $filter   = $request->get('filter', 'all');        // all | featured
        $category = $request->get('category', 'all');      // all | uncategorized | {slug}

        // ==========================================
        // BASE QUERY
        // ==========================================
        $query = Gallery::with('category');

        // Filter status
        if ($filter === 'featured') {
            $query->where('is_featured', 1);
        }

        // Filter kategori
        if ($category === 'uncategorized') {
            $query->where(function ($q) {
                $q->whereNull('category_id')
                ->orWhereDoesntHave('category');  // kategori soft-deleted
            });
        } elseif ($category !== 'all') {
            $cat = GalleryCategory::where('slug', $category)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            } else {
                $query->whereRaw('1 = 0'); // kosong
            }
        }

        // ==========================================
        // PAGINATION
        // ==========================================
        $galleries = $query
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        // ==========================================
        // COUNTS (buat tombol + dropdown)
        // ==========================================

        // Total semua gallery
        $countsAll = Gallery::count();

        // Count per kategori (dari DB)
        $categories = GalleryCategory::withCount('galleries')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Count "No Category" — null ATAU soft-deleted
        $uncategorizedCount = Gallery::where(function ($q) {
            $q->whereNull('category_id')
            ->orWhereDoesntHave('category');
        })->count();

        // Count featured — total + per kategori aktif
        $featuredTotal = Gallery::where('is_featured', 1)->count();
        $featuredCurrentCategory = 0;

        if ($category === 'uncategorized') {
            $featuredCurrentCategory = Gallery::where('is_featured', 1)
                ->where(function ($q) {
                    $q->whereNull('category_id')
                    ->orWhereDoesntHave('category');
                })
                ->count();
        } elseif ($category !== 'all') {
            $cat = GalleryCategory::where('slug', $category)->first();
            if ($cat) {
                $featuredCurrentCategory = Gallery::where('is_featured', 1)
                    ->where('category_id', $cat->id)
                    ->count();
            }
        } else {
            $featuredCurrentCategory = $featuredTotal; // kategori "Semua" → global
        }

        // ==========================================
        // LABEL TOMBOL KIRI (dinamis)
        // ==========================================
        $categoryLabel = 'All';
        if ($category === 'uncategorized') {
            $categoryLabel = 'No Category';
        } elseif ($category !== 'all') {
            $cat = GalleryCategory::where('slug', $category)->first();
            $categoryLabel = $cat ? $cat->name : 'All';
        }

        return view('pages.galleries-crud.index', [
            'galleries'                => $galleries,
            'categories'               => $categories,
            'filter'                   => $filter,
            'category'                 => $category,
            'categoryLabel'            => $categoryLabel,
            'countsAll'                => $countsAll,
            'uncategorizedCount'       => $uncategorizedCount,
            'featuredTotal'            => $featuredTotal,
            'featuredCurrentCategory'  => $featuredCurrentCategory,
            'layout'                   => 'side-menu',
        ]);
    }

    public function create()
    {
        $categories = GalleryCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('pages.galleries-crud.create', [
            'categories' => $categories,
            'layout'     => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'category_id' => 'required|integer|exists:gallery_categories,id',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['title', 'category_id', 'sort_order']);
        $data['is_active']   = $request->boolean('is_active') ? 1 : 0;
        $data['is_featured'] = $request->boolean('is_featured') ? 1 : 0;
        $data['sort_order']  = (int) ($request->sort_order ?? 0);

        // Set featured_at kalo di-featured
        $data['featured_at'] = $data['is_featured'] ? now() : null;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        DB::transaction(function () use ($data) {
            $gallery = Gallery::create($data);

            // FIFO: kalo featured → limit
            if ($data['is_featured']) {
                $this->enforceFeaturedLimit($gallery->id);
            }
        });

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Gallery created.');
    }

    public function edit(Gallery $gallery)
    {
        $categories = GalleryCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('pages.galleries-crud.edit', [
            'gallery'    => $gallery,
            'categories' => $categories,
            'layout'     => 'side-menu',
        ]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'category_id' => 'required|integer|exists:gallery_categories,id',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['title', 'category_id', 'sort_order']);

        $wasFeatured = (bool) $gallery->is_featured;
        $isFeatured  = $request->boolean('is_featured');

        $data['is_active']   = $request->boolean('is_active') ? 1 : 0;
        $data['is_featured'] = $isFeatured ? 1 : 0;
        $data['sort_order']  = (int) ($request->sort_order ?? 0);

        // featured_at cuma disentuh kalo status BERUBAH
        if (!$wasFeatured && $isFeatured) {
            $data['featured_at'] = now();       // 0 → 1: fresh
        } elseif ($wasFeatured && !$isFeatured) {
            $data['featured_at'] = null;        // 1 → 0: hapus
        }
        // 1 → 1: jangan sentuh featured_at

        if ($request->hasFile('image')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        DB::transaction(function () use ($data, $gallery, $wasFeatured, $isFeatured) {
            $gallery->update($data);

            // FIFO: kalo baru jadi featured (0 → 1)
            if (!$wasFeatured && $isFeatured) {
                $this->enforceFeaturedLimit($gallery->id);
            }
        });

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Gallery updated.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return back()->with('success', 'Gallery deleted.');
    }

    /**
     * Reorder galleries (drag & drop).
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array|min:1',
            'order.*' => 'integer|exists:galleries,id',
        ]);

        foreach ($request->order as $index => $id) {
            Gallery::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Bulk action.
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:featured,unfeatured,active,inactive,delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:galleries,id',
        ]);

        $ids    = $validated['ids'];
        $action = $validated['action'];

        DB::transaction(function () use ($ids, $action, &$msg) {
            switch ($action) {
                case 'featured':
                    // Set featured_at sekarang buat semua
                    Gallery::whereIn('id', $ids)->update([
                        'is_featured' => 1,
                        'featured_at' => now(),
                    ]);
                    // FIFO
                    $this->enforceFeaturedLimit();
                    $msg = count($ids) . ' photo(s) set to Featured';
                    break;

                case 'unfeatured':
                    Gallery::whereIn('id', $ids)->update([
                        'is_featured' => 0,
                        'featured_at' => null,
                    ]);
                    $msg = count($ids) . ' photo(s) unset from Featured';
                    break;

                case 'active':
                    Gallery::whereIn('id', $ids)->update(['is_active' => 1]);
                    $msg = count($ids) . ' photo(s) set to Active';
                    break;

                case 'inactive':
                    Gallery::whereIn('id', $ids)->update(['is_active' => 0]);
                    $msg = count($ids) . ' photo(s) set to Inactive';
                    break;

                case 'delete':
                    $galleries = Gallery::whereIn('id', $ids)->get();
                    foreach ($galleries as $g) {
                        if ($g->image && Storage::disk('public')->exists($g->image)) {
                            Storage::disk('public')->delete($g->image);
                        }
                        $g->delete();
                    }
                    $msg = count($ids) . ' photo(s) deleted';
                    break;
            }
        });

        return back()->with('success', $msg ?? 'Action completed.');
    }

    /**
     * ==========================================
     * FIFO — Batasi jumlah featured
     * ==========================================
     * Kalo featured > max → unset yang featured_at paling lama.
     * Kecuali $exceptId (yang baru di-set).
     */
    private function enforceFeaturedLimit(?int $exceptId = null): void
    {
        $max = Gallery::maxFeatured();

        $featuredCount = Gallery::where('is_featured', 1)->count();

        if ($featuredCount <= $max) {
            return;
        }

        $excess = $featuredCount - $max;

        $query = Gallery::where('is_featured', 1)
            ->orderBy('featured_at')
            ->orderBy('id'); // fallback kalo featured_at null

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        $query->take($excess)
            ->get()
            ->each(function ($g) {
                $g->update([
                    'is_featured' => 0,
                    'featured_at' => null,
                ]);
            });
    }
}