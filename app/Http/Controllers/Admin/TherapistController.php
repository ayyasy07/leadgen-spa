<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Therapist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TherapistController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Therapist::query();
        if ($filter === 'featured') {
            $query->where('show_on_about', 1);
        }

        $therapists = $query->orderBy('sort_order')->orderBy('name')->get();

        $counts = [
            'all'      => Therapist::count(),
            'featured' => Therapist::where('show_on_about', 1)->count(),
        ];

        return view('pages.therapists-crud.index', [
            'therapists' => $therapists,
            'counts'     => $counts,
            'filter'     => $filter,
            'layout'     => 'side-menu',
        ]);
    }

    public function create()
    {
        return view('pages.therapists-crud.create', [
            'layout' => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:therapists,slug',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'role'             => 'required|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'bio'              => 'nullable|string|max:1000',
        ]);

        $data = $request->only([
            'name', 'slug', 'meta_title', 'meta_description',
            'role', 'bio',
        ]);
        $data['is_active']     = $request->boolean('is_active') ? 1 : 0;
        $data['show_on_about'] = $request->boolean('show_on_about') ? 1 : 0;

        // ==========================================
        // NEW THERAPIST DI ATAS
        // Geser yang lama turun 1, new dapet sort_order = 0
        // ==========================================
        Therapist::where('sort_order', '>=', 0)->increment('sort_order');
        $data['sort_order'] = 0;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('therapists', 'public');
        }

        Therapist::create($data);

        return redirect()
            ->route('admin.therapists.index')
            ->with('success', 'Therapist created.');
    }

    public function edit(Therapist $therapist)
    {
        return view('pages.therapists-crud.edit', [
            'therapist' => $therapist,
            'layout'    => 'side-menu',
        ]);
    }

    public function update(Request $request, Therapist $therapist)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => [
                'nullable', 'string', 'max:255',
                Rule::unique('therapists', 'slug')->ignore($therapist->id),
            ],
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'role'             => 'required|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'bio'              => 'nullable|string|max:1000',
        ]);

        $data = $request->only([
            'name', 'slug', 'meta_title', 'meta_description',
            'role', 'bio',
        ]);
        $data['is_active']     = $request->boolean('is_active') ? 1 : 0;
        $data['show_on_about'] = $request->boolean('show_on_about') ? 1 : 0;

        // ==========================================
        // PENTING: sort_order GA DISENTUH pas update
        // Biar urutan drag & drop ga keganti
        // ==========================================
        // (ga ada $data['sort_order'] di sini)

        if ($request->hasFile('photo')) {
            if ($therapist->photo && Storage::disk('public')->exists($therapist->photo)) {
                Storage::disk('public')->delete($therapist->photo);
            }
            $data['photo'] = $request->file('photo')->store('therapists', 'public');
        }

        $therapist->update($data);

        return redirect()
            ->route('admin.therapists.index')
            ->with('success', 'Therapist updated.');
    }

    public function destroy(Therapist $therapist)
    {
        if ($therapist->photo && Storage::disk('public')->exists($therapist->photo)) {
            Storage::disk('public')->delete($therapist->photo);
        }

        $therapist->delete(); // soft delete

        return back()->with('success', 'Therapist deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array|min:1',
            'order.*' => 'integer|exists:therapists,id',
        ]);

        foreach ($request->order as $index => $id) {
            Therapist::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}