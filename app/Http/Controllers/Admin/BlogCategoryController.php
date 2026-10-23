<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'asc');
        $sort = in_array($sort, ['asc', 'desc']) ? $sort : 'asc';

        $categories = BlogCategory::withCount('blogs')
                        ->orderBy('name', $sort)
                        ->get();

        return view('pages.blog-categories-crud.index', [
            'categories' => $categories,
            'sort'       => $sort,
            'layout'     => 'side-menu',
        ]);
    }

    public function create()
    {
        return view('pages.blog-categories-crud.create', [
            'layout' => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:blog_categories,name',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        BlogCategory::create([
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'sort_order' => (int) ($request->sort_order ?? 0),
        ]);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category created.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('pages.blog-categories-crud.edit', [
            'category' => $blogCategory,
            'layout'   => 'side-menu',
        ]);
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:blog_categories,name,' . $blogCategory->id,
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $blogCategory->update([
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'sort_order' => (int) ($request->sort_order ?? 0),
        ]);

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return back()->with('success', 'Category deleted.');
    }
}