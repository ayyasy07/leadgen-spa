<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\Therapist;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['groups' => []]);
        }

        $groups = [];

        // ==============================
        // Treatments
        // ==============================
        $treatments = Treatment::where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'title'    => $t->name,
                'subtitle' => $t->category ?? 'Treatment',
                'url'      => route('admin.treatments.edit', $t->id),
            ])->toArray();

        if ($treatments) {
            $groups[] = [
                'label' => 'Treatments',
                'icon'  => 'scissors',
                'items' => $treatments,
            ];
        }

        // ==============================
        // Packages
        // ==============================
        $packages = Package::where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'title'    => $p->name,
                'subtitle' => 'Rp ' . number_format($p->price ?? 0, 0, ',', '.'),
                'url'      => route('admin.packages.edit', $p->id),
            ])->toArray();

        if ($packages) {
            $groups[] = [
                'label' => 'Packages',
                'icon'  => 'package',
                'items' => $packages,
            ];
        }

        // ==============================
        // Blogs
        // ==============================
        $blogs = Blog::where('title', 'like', "%{$q}%")
            ->orWhere('content', 'like', "%{$q}%")
            ->orWhere('excerpt', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($b) => [
                'title'    => $b->title,
                'subtitle' => $b->category->name ?? 'Blog',
                'url'      => route('admin.blogs.edit', $b->id),
            ])->toArray();

        if ($blogs) {
            $groups[] = [
                'label' => 'Blogs',
                'icon'  => 'file-text',
                'items' => $blogs,
            ];
        }

        // ==============================
        // Gallery
        // ==============================
        $galleries = Gallery::where('title', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($g) => [
                'title'    => $g->title ?: '(Untitled)',
                'subtitle' => $g->category->name ?? 'Gallery',
                'url'      => route('admin.galleries.edit', $g->id),
            ])->toArray();

        if ($galleries) {
            $groups[] = [
                'label' => 'Gallery',
                'icon'  => 'image',
                'items' => $galleries,
            ];
        }

        // ==============================
        // Therapists
        // ==============================
        $therapists = Therapist::where('name', 'like', "%{$q}%")
            ->orWhere('role', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'title'    => $t->name,
                'subtitle' => $t->role ?? 'Therapist',
                'url'      => route('admin.therapists.edit', $t->id),
            ])->toArray();

        if ($therapists) {
            $groups[] = [
                'label' => 'Therapists',
                'icon'  => 'users',
                'items' => $therapists,
            ];
        }

        // ==============================
        // Testimonials
        // ==============================
        $testimonials = Testimonial::where('name', 'like', "%{$q}%")
            ->orWhere('message', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'title'    => $t->name,
                'subtitle' => $t->role ?? 'Testimonial',
                'url'      => route('admin.testimonials.edit', $t->id),
            ])->toArray();

        if ($testimonials) {
            $groups[] = [
                'label' => 'Testimonials',
                'icon'  => 'message-square',
                'items' => $testimonials,
            ];
        }

        // ==============================
        // Users
        // ==============================
        $users = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($u) => [
                'title'    => $u->name,
                'subtitle' => $u->email,
                'url'      => route('admin.users.edit', $u->id),
            ])->toArray();

        if ($users) {
            $groups[] = [
                'label' => 'Users',
                'icon'  => 'user',
                'items' => $users,
            ];
        }

        // ==============================
        // Bookings (by code / customer name)
        // ==============================
        $bookings = Booking::where('booking_code', 'like', "%{$q}%")
            ->orWhere('customer_name', 'like', "%{$q}%")
            ->orWhere('customer_phone', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($b) => [
                'title'    => $b->booking_code . ' — ' . $b->customer_name,
                'subtitle' => $b->booking_date->format('d M Y') . ' • ' . ucfirst($b->status),
                'url'      => route('admin.bookings.show', $b->id),
            ])->toArray();

        if ($bookings) {
            $groups[] = [
                'label' => 'Bookings',
                'icon'  => 'calendar',
                'items' => $bookings,
            ];
        }

        // ==============================
        // Contact Messages
        // ==============================
        $messages = ContactMessage::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('subject', 'like', "%{$q}%")
            ->orWhere('message', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($m) => [
                'title'    => $m->name,
                'subtitle' => $m->subject ?: '(No Subject)',
                'url'      => route('admin.contact-messages.show', $m->id),
            ])->toArray();

        if ($messages) {
            $groups[] = [
                'label' => 'Messages',
                'icon'  => 'inbox',
                'items' => $messages,
            ];
        }

        return response()->json(['groups' => $groups]);
    }
}