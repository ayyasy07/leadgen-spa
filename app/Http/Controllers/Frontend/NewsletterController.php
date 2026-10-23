<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('newsletter_error', 'Invalid email address.');
        }

        $email = strtolower(trim($request->email));

        // Cek existing (termasuk yang soft-deleted)
        $existing = NewsletterSubscriber::withTrashed()
            ->where('email', $email)
            ->first();

        if ($existing) {
            // Kalo pernah unsubscribe / soft-deleted → aktifkan lagi
            if ($existing->trashed() || !$existing->is_active) {
                $existing->restore();
                $existing->update([
                    'is_active'       => 1,
                    'subscribed_at'   => now(),
                    'unsubscribed_at' => null,
                ]);
                return back()->with('newsletter_success', 'Thank you! Your email has been reactivated.');
            }

            return back()->with('newsletter_info', 'Your email is already registered.');
        }

        NewsletterSubscriber::create([
            'email'         => $email,
            'is_active'     => 1,
            'subscribed_at' => now(),
        ]);

        return back()->with('newsletter_success', 'Thank you! Your email has been registered.');
    }
}