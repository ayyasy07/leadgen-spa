<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $subscribers = $query->orderByDesc('created_at')->get();

        return view('pages.newsletters-crud.index', [
            'subscribers'  => $subscribers,
            'filterStatus' => $request->get('status', 'all'),
            'filterSearch' => $request->get('search', ''),
            'layout'       => 'side-menu',
        ]);
    }

    public function compose()
    {
        return view('pages.newsletters-crud.compose', [
            'layout' => 'side-menu',
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject'   => 'required|string|max:255',
            'body'      => 'required|string',
            'recipient' => 'required|in:active,all',
        ]);

        // TODO: Setup SMTP dulu, baru aktifin ini.
        // Sementara return info ke user.

        return redirect()
            ->route('admin.newsletters.index')
            ->with('success', 'Send feature is not active yet. Set up SMTP first to enable it.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:newsletter_subscribers,id',
        ]);

        $count = NewsletterSubscriber::whereIn('id', $request->ids)->delete();

        return back()->with('success', "{$count} subscriber(s) deleted.");
    }

    public function destroy(NewsletterSubscriber $newsletter)
    {
        $newsletter->delete();

        return back()->with('success', 'Subscriber deleted.');
    }

    public function toggle(NewsletterSubscriber $newsletter)
    {
        $newActive = !$newsletter->is_active;

        $data = [
            'is_active' => $newActive ? 1 : 0,
        ];

        if ($newActive) {
            $data['subscribed_at']   = $newsletter->subscribed_at ?? now();
            $data['unsubscribed_at'] = null;
        } else {
            $data['unsubscribed_at'] = now();
        }

        $newsletter->update($data);

        return back()->with('success', 'Subscriber status updated.');
    }

    public function export(): StreamedResponse
    {
        $subscribers = NewsletterSubscriber::where('is_active', 1)
            ->orderBy('email')
            ->get();

        $filename = 'newsletter-subscribers-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Email', 'Subscribed At', 'Status']);

            foreach ($subscribers as $s) {
                fputcsv($file, [
                    $s->email,
                    $s->subscribed_at ? $s->subscribed_at->format('Y-m-d H:i') : '-',
                    $s->is_active ? 'Active' : 'Inactive',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}