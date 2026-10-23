<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('is_active', 1)
            ->latest()
            ->get();

        // Siapin data buat JS (biar gak perlu mapping di blade)
        $testimonialsJson = $testimonials->map(function ($t) {
            return [
                'id'      => $t->id,
                'name'    => $t->name,
                'role'    => $t->role ?? 'Customer',
                'rating'  => (int) $t->rating,
                'message' => $t->message,
                'photo'   => $t->photo
                    ? asset('storage/'.$t->photo)
                    : 'https://ui-avatars.com/api/?name='.urlencode($t->name).'&background=f76c6c&color=fff&size=200',
            ];
        })->values()->all();

        return view('user.pages.testimonials', compact('testimonials', 'testimonialsJson'));
    }
}