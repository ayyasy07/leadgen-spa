<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::orderBy('sort_order')->orderBy('id')->get();

        return view('pages.hero-sliders-crud.index', [
            'sliders' => $sliders,
            'layout'  => 'side-menu',
        ]);
    }

    public function create()
    {
        return view('pages.hero-sliders-crud.create', [
            'layout' => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'eyebrow'    => 'nullable|string|max:255',
            'title'      => 'required|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['eyebrow', 'title', 'sort_order']);
        $data['is_active']  = $request->boolean('is_active') ? 1 : 0;
        $data['sort_order'] = (int) ($request->sort_order ?? 0);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero-sliders', 'public');
        }

        HeroSlider::create($data);

        return redirect()
            ->route('admin.hero-sliders.index')
            ->with('success', 'Hero slider created.');
    }

    public function edit(HeroSlider $heroSlider)
    {
        return view('pages.hero-sliders-crud.edit', [
            'slider' => $heroSlider,
            'layout' => 'side-menu',
        ]);
    }

    public function update(Request $request, HeroSlider $heroSlider)
    {
        $request->validate([
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'eyebrow'    => 'nullable|string|max:255',
            'title'      => 'required|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['eyebrow', 'title', 'sort_order']);
        $data['is_active']  = $request->boolean('is_active') ? 1 : 0;
        $data['sort_order'] = (int) ($request->sort_order ?? 0);

        if ($request->hasFile('image')) {
            if ($heroSlider->image && Storage::disk('public')->exists($heroSlider->image)) {
                Storage::disk('public')->delete($heroSlider->image);
            }
            $data['image'] = $request->file('image')->store('hero-sliders', 'public');
        }

        $heroSlider->update($data);

        return redirect()
            ->route('admin.hero-sliders.index')
            ->with('success', 'Hero slider updated.');
    }

    public function destroy(HeroSlider $heroSlider)
    {
        if ($heroSlider->image && Storage::disk('public')->exists($heroSlider->image)) {
            Storage::disk('public')->delete($heroSlider->image);
        }

        $heroSlider->delete();

        return back()->with('success', 'Hero slider deleted.');
    }

    /**
     * Reorder hero sliders (drag & drop).
     * Body: { order: [id1, id2, id3, ...] }
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array|min:1',
            'order.*' => 'integer|exists:hero_sliders,id',
        ]);

        foreach ($request->order as $index => $id) {
            HeroSlider::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}