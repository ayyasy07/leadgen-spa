<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryCategoryController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'asc');
        $sort = in_array($sort, ['asc', 'desc']) ? $sort : 'asc';

        $categories = GalleryCategory::withCount('galleries')
                        ->orderBy('name', $sort)
                        ->get();

        return view('pages.gallery-categories-crud.index', [
            'categories' => $categories,
            'sort'       => $sort,
            'layout'     => 'side-menu',
        ]);
    }

    public function create()
    {
        return view('pages.gallery-categories-crud.create', [
            'layout' => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:gallery_categories,name',
        ]);

        GalleryCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', 'Category created.');
    }

    public function edit(GalleryCategory $galleryCategory)
    {
        return view('pages.gallery-categories-crud.edit', [
            'category' => $galleryCategory,
            'layout'   => 'side-menu',
        ]);
    }

    public function update(Request $request, GalleryCategory $galleryCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:gallery_categories,name,' . $galleryCategory->id,
        ]);

        $galleryCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()
            ->route('admin.gallery-categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(GalleryCategory $galleryCategory)
    {
        $galleryCategory->delete();

        return back()->with('success', 'Category deleted.');
    }
}