<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    /**
     * List semua treatment + filter kategori (query string).
     */
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');

        $query = Treatment::where('is_active', 1)
            ->with('prices')
            ->orderBy('name');

        if ($kategori) {
            // Cocokkan dengan slug kategori
            $allCategories = Treatment::where('is_active', 1)
                ->pluck('category')
                ->unique();

            $matchedCategory = $allCategories->first(function ($cat) use ($kategori) {
                return \Str::slug($cat) === $kategori;
            });

            if ($matchedCategory) {
                $query->where('category', $matchedCategory);
            }
        }

        $treatments = $query->get();

        // Semua kategori unik buat filter tabs
        $categories = Treatment::where('is_active', 1)
            ->pluck('category')
            ->unique()
            ->values();

        return view('user.pages.treatments', compact(
            'treatments',
            'categories',
            'kategori'
        ));
    }

    /**
     * Detail treatment berdasarkan slug.
     */
    public function show($slug)
    {
        $treatment = Treatment::where('slug', $slug)
            ->where('is_active', 1)
            ->with('prices')
            ->firstOrFail();

        // Treatment lain di kategori yang sama (buat rekomendasi)
        $related = Treatment::where('is_active', 1)
            ->where('category', $treatment->category)
            ->where('id', '!=', $treatment->id)
            ->with('prices')
            ->take(3)
            ->get();

        return view('user.pages.treatment-detail', compact(
            'treatment',
            'related'
        ));
    }
}