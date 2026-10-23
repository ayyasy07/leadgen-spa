<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DarkModeController extends Controller
{
    /**
     * Toggle dark mode via session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function switch(Request $request)
    {
        // Toggle dark mode
        session([
            'dark_mode' => !session('dark_mode', false)
        ]);

        return back();
    }
}