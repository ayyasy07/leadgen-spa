<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * LIST TESTIMONIALS
     */
    public function index()
    {
        $testimonials = Testimonial::latest()->get();

        return view('pages.testimonials-crud.index', [
            'testimonials' => $testimonials,
            'layout' => 'side-menu',
        ]);
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        return view('pages.testimonials-crud.create', [
            'layout' => 'side-menu',
        ]);
    }

    /**
     * SIMPAN DATA BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'nullable|string|max:255',
            'rating'  => 'required|integer|min:1|max:5',
            'message' => 'required|string',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'name', 'role', 'rating', 'message'
        ]);

        $data['is_active'] = $request->boolean('is_active') ? 1 : 0;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial created.');
    }

    /**
     * FORM EDIT
     */
    public function edit(Testimonial $testimonial)
    {
        return view('pages.testimonials-crud.edit', [
            'testimonial' => $testimonial,
            'layout' => 'side-menu',
        ]);
    }

    /**
     * UPDATE DATA
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'nullable|string|max:255',
            'rating'  => 'required|integer|min:1|max:5',
            'message' => 'required|string',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'name', 'role', 'rating', 'message'
        ]);

        $data['is_active'] = $request->boolean('is_active') ? 1 : 0;

        if ($request->hasFile('photo')) {
            if ($testimonial->photo && Storage::disk('public')->exists($testimonial->photo)) {
                Storage::disk('public')->delete($testimonial->photo);
            }
            $data['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated.');
    }

    /**
     * DELETE (SOFT DELETE)
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return back()->with('success', 'Testimonial deleted.');
    }
}