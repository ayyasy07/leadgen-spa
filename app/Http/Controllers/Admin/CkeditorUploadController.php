<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CkeditorUploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $request->validate([
                'upload' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            ]);

            $file = $request->file('upload');
            $path = $file->store('blog-content', 'public');

            return response()->json([
                'uploaded' => 1,
                'fileName' => $file->getClientOriginalName(),
                'url'      => asset('storage/' . $path),
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error'    => ['message' => 'Tidak ada file yang diupload.'],
        ]);
    }
}