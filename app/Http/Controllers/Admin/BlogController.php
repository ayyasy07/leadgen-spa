<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('category');

        // ===== FILTER KATEGORI =====
        $category = $request->get('category', 'all');
        if ($category !== 'all') {
            if ($category === 'uncategorized') {
                $query->whereNull('category_id');
            } else {
                $query->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            }
        }

        // ===== FILTER STATUS =====
        $status = $request->get('status', 'all');
        if (!in_array($status, ['all', 'published', 'draft'])) {
            $status = 'all';
        }
        if ($status === 'published') {
            $query->where('is_published', 1);
        } elseif ($status === 'draft') {
            $query->where('is_published', 0);
        }

        $blogs = $query
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        // ===== KATEGORI DINAMIS + COUNT =====
        $categories = BlogCategory::withCount('blogs')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $countsPerCategory = $categories->pluck('blogs_count', 'slug');

        $uncategorizedCount = Blog::whereNull('category_id')->count();
        $countsAll = Blog::count();

        // Label kategori yang lagi aktif
        $categoryLabel = 'Category';
        if ($category === 'uncategorized') {
            $categoryLabel = 'No Category';
        } elseif ($category !== 'all') {
            $activeCat = $categories->firstWhere('slug', $category);
            $categoryLabel = $activeCat ? $activeCat->name : 'Category';
        }

        // ===== STATUS COUNT (per kategori aktif) =====
        $statusBaseQuery = Blog::query();
        if ($category !== 'all') {
            if ($category === 'uncategorized') {
                $statusBaseQuery->whereNull('category_id');
            } else {
                $statusBaseQuery->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            }
        }

        $publishedCount = (clone $statusBaseQuery)->where('is_published', 1)->count();
        $draftCount = (clone $statusBaseQuery)->where('is_published', 0)->count();

        return view('pages.blogs-crud.index', [
            'blogs'              => $blogs,
            'categories'         => $categories,
            'countsPerCategory'  => $countsPerCategory,
            'uncategorizedCount' => $uncategorizedCount,
            'countsAll'          => $countsAll,
            'categoryLabel'      => $categoryLabel,
            'category'           => $category,
            'status'             => $status,
            'publishedCount'     => $publishedCount,
            'draftCount'         => $draftCount,
            'layout'             => 'side-menu',
        ]);
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('pages.blogs-crud.create', [
            'categories' => $categories,
            'layout'     => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blogs,slug',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'category_id'      => 'nullable|integer|exists:blog_categories,id',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'excerpt'          => 'nullable|string|max:210',
            'content'          => 'required|string',
            'is_published'     => 'nullable|boolean',
            'published_at'     => 'nullable|date',
            'sort_order'       => 'nullable|integer|min:0',

            // EXTRA
            'direction'        => 'nullable|string|in:ltr,rtl',
            'text_align'       => 'nullable|string|in:left,center,right,justify',
            'line_height'      => 'nullable|string|in:1.4,1.6,1.8,2.0',
            'caption'          => 'nullable|string|max:255',
            'keywords'         => 'nullable|string|max:500',
            'author_name'      => 'nullable|string|max:255',
            'show_author'      => 'nullable|boolean',
            'gallery'          => 'nullable|array',
            'gallery.*'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $request->only([
            'title', 'slug', 'meta_title', 'meta_description',
            'category_id', 'excerpt', 'content', 'published_at', 'sort_order',
            'direction', 'text_align', 'line_height',
            'caption', 'keywords', 'author_name',
        ]);

        $data['is_published']   = $request->boolean('is_published') ? 1 : 0;
        $data['sort_order']     = (int) ($request->sort_order ?? 0);
        $data['direction']      = $request->direction ?? 'ltr';
        $data['text_align']     = $request->text_align ?? 'left';
        $data['line_height']    = $request->line_height ?? '1.6';
        $data['show_author']    = $request->boolean('show_author') ? 1 : 0;
        $data['author_name']    = $request->author_name ?? auth()->user()->name ?? 'Admin';

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // ===== THUMBNAIL =====
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        // ===== GALLERY (multiple) — FLAT di blogs/ =====
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                if ($file && $file->isValid()) {
                    $galleryPaths[] = $file->store('blogs', 'public');
                }
            }
            $data['gallery'] = !empty($galleryPaths) ? $galleryPaths : null;
        } else {
            $data['gallery'] = null;
        }

        Blog::create($data);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog created.');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('sort_order')->orderBy('name')->get();

        return view('pages.blogs-crud.edit', [
            'blog'       => $blog,
            'categories' => $categories,
            'layout'     => 'side-menu',
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => [
                'nullable', 'string', 'max:255',
                Rule::unique('blogs', 'slug')->ignore($blog->id),
            ],
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'category_id'      => 'nullable|integer|exists:blog_categories,id',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'is_published'     => 'nullable|boolean',
            'published_at'     => 'nullable|date',
            'sort_order'       => 'nullable|integer|min:0',

            // EXTRA
            'direction'        => 'nullable|string|in:ltr,rtl',
            'text_align'       => 'nullable|string|in:left,center,right,justify',
            'line_height'      => 'nullable|string|in:1.4,1.6,1.8,2.0',
            'caption'          => 'nullable|string|max:255',
            'keywords'         => 'nullable|string|max:500',
            'author_name'      => 'nullable|string|max:255',
            'show_author'      => 'nullable|boolean',
            'gallery'          => 'nullable|array',
            'gallery.*'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $request->only([
            'title', 'slug', 'meta_title', 'meta_description',
            'category_id', 'excerpt', 'content', 'published_at', 'sort_order',
            'direction', 'text_align', 'line_height',
            'caption', 'keywords', 'author_name',
        ]);

        $data['is_published']   = $request->boolean('is_published') ? 1 : 0;
        $data['sort_order']     = (int) ($request->sort_order ?? 0);
        $data['direction']      = $request->direction ?? 'ltr';
        $data['text_align']     = $request->text_align ?? 'left';
        $data['line_height']    = $request->line_height ?? '1.6';
        $data['show_author']    = $request->boolean('show_author') ? 1 : 0;
        $data['author_name']    = $request->author_name ?? auth()->user()->name ?? 'Admin';

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // ===== THUMBNAIL =====
        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        // ===== GALLERY — replace kalo upload baru =====
        if ($request->hasFile('gallery')) {
            // Hapus gallery lama
            if ($blog->gallery && is_array($blog->gallery)) {
                foreach ($blog->gallery as $oldImg) {
                    if ($oldImg && Storage::disk('public')->exists($oldImg)) {
                        Storage::disk('public')->delete($oldImg);
                    }
                }
            }

            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                if ($file && $file->isValid()) {
                    $galleryPaths[] = $file->store('blogs', 'public');
                }
            }
            $data['gallery'] = !empty($galleryPaths) ? $galleryPaths : null;
        }

        $blog->update($data);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog updated.');
    }

    public function destroy(Blog $blog)
    {
        // Hapus thumbnail
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        // Hapus gallery
        if ($blog->gallery && is_array($blog->gallery)) {
            foreach ($blog->gallery as $img) {
                if ($img && Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }

        $blog->delete();

        return back()->with('success', 'Blog deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array|min:1',
            'order.*' => 'integer|exists:blogs,id',
        ]);

        foreach ($request->order as $index => $id) {
            Blog::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}