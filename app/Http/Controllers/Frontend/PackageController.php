<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', 1)
            ->with('items')
            ->latest()
            ->get();

        return view('user.pages.packages', compact('packages'));
    }
}