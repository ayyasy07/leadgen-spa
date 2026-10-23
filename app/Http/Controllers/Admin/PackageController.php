<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    const MAX_FEATURED = 3;

    public function index(Request $request)
    {
        $query = Package::with('items');

        // Filter unit (tab)
        $unit = $request->get('unit', 'all');
        if (!in_array($unit, ['all', 'person', 'couple'])) {
            $unit = 'all';
        }
        if ($unit !== 'all') {
            $query->where('unit', $unit);
        }

        // Filter featured
        $filter = $request->get('featured') == '1' ? 'featured' : 'all';
        if ($filter === 'featured') {
            $query->where('is_featured', 1)->orderBy('featured_at');
        } else {
            $query->latest();
        }

        $packages = $query->get();

        // Count per unit (global, bukan hasil filter)
        $countsPerUnit = Package::query()
            ->selectRaw('unit, COUNT(*) as total')
            ->whereNotNull('unit')
            ->groupBy('unit')
            ->pluck('total', 'unit');

        $countsAll = Package::count();

        // Featured count (di unit yang lagi dipilih)
        $featuredQuery = Package::where('is_featured', 1);
        if ($unit !== 'all') {
            $featuredQuery->where('unit', $unit);
        }
        $featuredCurrentUnit = $featuredQuery->count();

        return view('pages.packages-crud.index', [
            'packages'            => $packages,
            'countsPerUnit'       => $countsPerUnit,
            'countsAll'           => $countsAll,
            'featuredCurrentUnit' => $featuredCurrentUnit,
            'unit'                => $unit,
            'filter'              => $filter,
            'layout'              => 'side-menu',
        ]);
    }

    public function create()
    {
        $treatments = Treatment::with('prices')->where('is_active', 1)->get();

        return view('pages.packages-crud.create', [
            'treatments' => $treatments,
            'layout'     => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:packages,slug',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'price'            => 'required|integer|min:1',
            'duration'         => 'required|integer|min:1',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'items'                => 'required|array|min:2',
            'items.*.name'         => 'required|string|max:255',
            'items.*.duration'     => 'required|integer|min:1',
            'items.*.price'        => 'required|integer|min:0',
            'items.*.treatment_id' => 'nullable|integer|exists:treatments,id',
            'items.*.is_custom'    => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($request) {
            [$originalPrice, $saving, $savingPercent] = $this->calculateSummary($request);

            $isFeatured = $request->boolean('is_featured');

            $data = [
                'name'             => $request->name,
                'slug'             => $request->slug,
                'meta_title'       => $request->meta_title,
                'meta_description' => $request->meta_description,
                'price'            => (int) $request->price,
                'duration'         => (int) $request->duration,
                'unit'             => $request->unit ?? 'person',
                'description'      => $request->description,
                'is_active'        => $request->boolean('is_active') ? 1 : 0,
                'is_featured'      => $isFeatured ? 1 : 0,
                'is_popular'       => $request->boolean('is_popular') ? 1 : 0,
                'featured_at'      => $isFeatured ? now() : null,
                'original_price'   => $originalPrice,
                'saving'           => $saving,
                'saving_percent'   => $savingPercent,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('packages', 'public');
            }

            $package = Package::create($data);

            if ($isFeatured) {
                $this->enforceFeaturedLimit($package->id);
            }

            $this->saveItems($package, $request->items);

            return redirect()
                ->route('admin.packages.index')
                ->with('success', 'Package created.');
        });
    }

    public function edit(Package $package)
    {
        $package->load('items');
        $treatments = Treatment::with('prices')->where('is_active', 1)->get();

        return view('pages.packages-crud.edit', [
            'package'    => $package,
            'treatments' => $treatments,
            'layout'     => 'side-menu',
        ]);
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => [
                'nullable', 'string', 'max:255',
                Rule::unique('packages', 'slug')->ignore($package->id),
            ],
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'price'            => 'required|integer|min:1',
            'duration'         => 'required|integer|min:1',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'items'                => 'required|array|min:2',
            'items.*.name'         => 'required|string|max:255',
            'items.*.duration'     => 'required|integer|min:1',
            'items.*.price'        => 'required|integer|min:0',
            'items.*.treatment_id' => 'nullable|integer|exists:treatments,id',
            'items.*.is_custom'    => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($request, $package) {
            [$originalPrice, $saving, $savingPercent] = $this->calculateSummary($request);

            $wasFeatured = (bool) $package->is_featured;
            $isFeatured  = $request->boolean('is_featured');

            $data = [
                'name'             => $request->name,
                'slug'             => $request->slug,
                'meta_title'       => $request->meta_title,
                'meta_description' => $request->meta_description,
                'price'            => (int) $request->price,
                'duration'         => (int) $request->duration,
                'unit'             => $request->unit ?? 'person',
                'description'      => $request->description,
                'is_active'        => $request->boolean('is_active') ? 1 : 0,
                'is_featured'      => $isFeatured ? 1 : 0,
                'is_popular'       => $request->boolean('is_popular') ? 1 : 0,
                'original_price'   => $originalPrice,
                'saving'           => $saving,
                'saving_percent'   => $savingPercent,
            ];

            // FIFO logic: cuma sentuh featured_at kalau statusnya BERUBAH
            if (!$wasFeatured && $isFeatured) {
                // 0 → 1: fresh featured
                $data['featured_at'] = now();
            } elseif ($wasFeatured && !$isFeatured) {
                // 1 → 0: un-featured
                $data['featured_at'] = null;
            }
            // 1 → 1: JANGAN sentuh featured_at (biar FIFO akurat)

            if ($request->hasFile('image')) {
                if ($package->image && Storage::disk('public')->exists($package->image)) {
                    Storage::disk('public')->delete($package->image);
                }
                $data['image'] = $request->file('image')->store('packages', 'public');
            }

            $package->update($data);

            if (!$wasFeatured && $isFeatured) {
                $this->enforceFeaturedLimit($package->id);
            }

            $package->items()->delete();
            $this->saveItems($package, $request->items);

            return redirect()
                ->route('admin.packages.index')
                ->with('success', 'Package updated.');
        });
    }

    public function destroy(Package $package)
    {
        $package->items()->delete();
        $package->delete();

        return back()->with('success', 'Package deleted.');
    }

    /**
     * FIFO: kalau featured > MAX_FEATURED, unset yang paling lama
     * (exclude package yang baru aja di-set biar ga ke-unset sendiri)
     */
    private function enforceFeaturedLimit(int $exceptId): void
    {
        $featuredCount = Package::where('is_featured', 1)->count();

        if ($featuredCount <= self::MAX_FEATURED) {
            return;
        }

        $excess = $featuredCount - self::MAX_FEATURED;

        Package::where('is_featured', 1)
            ->where('id', '!=', $exceptId)
            ->orderBy('featured_at')
            ->take($excess)
            ->get()
            ->each(function ($pkg) {
                $pkg->update([
                    'is_featured' => 0,
                    'featured_at' => null,
                ]);
            });
    }

    private function calculateSummary(Request $request): array
    {
        $originalPrice = 0;

        foreach ($request->items as $item) {
            $originalPrice += (int) ($item['price'] ?? 0);
        }

        $packagePrice  = (int) $request->price;
        $saving        = max($originalPrice - $packagePrice, 0);
        $savingPercent = $originalPrice > 0
            ? (int) round(($saving / $originalPrice) * 100)
            : 0;

        return [$originalPrice, $saving, $savingPercent];
    }

    private function saveItems(Package $package, array $items): void
    {
        foreach ($items as $item) {
            $package->items()->create([
                'treatment_id' => $item['treatment_id'] ?? null,
                'name'         => $item['name'],
                'duration'     => (int) $item['duration'],
                'price'        => (int) $item['price'],
                'is_custom'    => !empty($item['is_custom']) ? 1 : 0,
            ]);
        }
    }
}