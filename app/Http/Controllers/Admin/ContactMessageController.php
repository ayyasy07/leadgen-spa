<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /* =====================
       INDEX
    ===================== */
    public function index(Request $request)
    {
        $tab        = $request->get('tab', 'inbox');   // inbox | archived
        $status     = $request->get('status', 'all');  // all | unread | read | replied
        $search     = $request->get('search', '');

        $query = ContactMessage::query();

        // Tab
        if ($tab === 'archived') {
            $query->archived();
        } else {
            $query->inbox();
        }

        // Sub-filter status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $messages = $query->orderByDesc('created_at')->get();

        // Counts
        $counts = [
            'inbox'    => ContactMessage::inbox()->count(),
            'archived' => ContactMessage::archived()->count(),
        ];

        return view('pages.contact-messages.index', [
            'messages' => $messages,
            'counts'   => $counts,
            'tab'      => $tab,
            'status'   => $status,
            'search'   => $search,
            'layout'   => 'side-menu',
        ]);
    }

    /* =====================
       SHOW
    ===================== */
    public function show(ContactMessage $contactMessage)
    {
        // Auto mark as read (kalo unread)
        if ($contactMessage->status === 'unread') {
            $contactMessage->update([
                'status'  => 'read',
                'read_at' => now(),
            ]);
        }

        return view('pages.contact-messages.show', [
            'message' => $contactMessage,
            'layout'  => 'side-menu',
        ]);
    }

    /* =====================
       ARCHIVE / UNARCHIVE (TOGGLE)
    ===================== */
    public function archive(ContactMessage $contactMessage)
    {
        if ($contactMessage->isArchived()) {
            $contactMessage->unarchive();
            return back()->with('success', 'Message has been unarchived.');
        }

        if (!$contactMessage->canBeArchived()) {
            return back()->with('error', 'Message has not been read yet. Read it before archiving.');
        }

        $contactMessage->archive();
        return back()->with('success', 'Message archived.');
    }

    /* =====================
       BULK ACTION
    ===================== */
    public function bulk(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:contact_messages,id',
            'action' => 'required|in:archive,unarchive,delete',
        ]);

        $ids    = $request->ids;
        $action = $request->action;

        $messages = ContactMessage::whereIn('id', $ids)->get();

        $affected = 0;
        $skipped  = 0;

        foreach ($messages as $m) {
            switch ($action) {
                case 'archive':
                    if ($m->canBeArchived() && !$m->isArchived()) {
                        $m->archive();
                        $affected++;
                    } else {
                        $skipped++;
                    }
                    break;

                case 'unarchive':
                    if ($m->isArchived()) {
                        $m->unarchive();
                        $affected++;
                    } else {
                        $skipped++;
                    }
                    break;

                case 'delete':
                    $m->delete();
                    $affected++;
                    break;
            }
        }

        $msg = match ($action) {
            'archive'   => "{$affected} message(s) archived" . ($skipped > 0 ? ", {$skipped} skipped (unread)" : ''),
            'unarchive' => "{$affected} message(s) unarchived",
            'delete'    => "{$affected} message(s) deleted",
        };

        return back()->with('success', $msg);
    }

    /* =====================
       REPLY — DISABLED SAMPAI SMTP READY
    ===================== */
    public function reply(Request $request, ContactMessage $contactMessage)
    {
        return back()->with('error', 'Reply feature is not active yet. Set up SMTP first to enable it.');
    }

    /* =====================
       DESTROY (single)
    ===================== */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Message deleted.');
    }
}