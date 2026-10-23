<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Therapist;

class PageController extends Controller
{
    /**
     * About Us page — therapist dari DB (is_active = 1).
     */
        public function about()
    {
        $therapists = Therapist::forAbout()->get();

        return view('user.pages.about-us', compact('therapists'));
    }
}