<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    /**
     * Ambil notif terbaru — dipake buat polling bell icon.
     * Return JSON: { items: [...], total: N, unread: M }
     */
    public function index(Request $request)
    {
        $adminId = auth()->id() ?? 0;
        $cacheKey = 'admin_notif_' . $adminId;

        $payload = Cache::remember($cacheKey, 15, function () {
            return $this->buildPayload();
        });

        return response()->json($payload);
    }

    /**
     * Bangun payload notif: gabung dari 4 sumber, sort created_at DESC.
     */
    private function buildPayload(): array
    {
        $items = [];

        // ===== 1. BOOKING PAID (Pembayaran Diterima) =====
        // Ganti STATUS_CONFIRMED jadi STATUS_PAID
        $paid = Booking::where('status', Booking::STATUS_PAID)
            ->latest('paid_at')
            ->take(20)
            ->get();

        foreach ($paid as $b) {
            $items[] = [
                'id'         => 'booking-paid-' . $b->id,
                'type'       => 'booking_paid',
                'title'      => 'Pembayaran diterima: ' . ($b->customer_name ?: 'Customer'),
                'subtitle'   => $b->booking_code . ' · ' . $this->formatDate($b->booking_date),
                'url'        => route('admin.bookings.show', $b->id),
                'icon'       => 'check-circle',
                'tone'       => 'primary',
                'time_ago'   => $b->paid_at ? $b->paid_at->diffForHumans() : '',
                'created_at' => $b->paid_at ? $b->paid_at->toIso8601String() : now()->toIso8601String(),
            ];
        }

        // ===== 2. BOOKING CANCELLED =====
        $cancelled = Booking::where('status', Booking::STATUS_CANCELLED)
            ->latest('cancelled_at')
            ->take(20)
            ->get();

        foreach ($cancelled as $b) {
            $subtitle = $b->booking_code;
            if ($b->cancel_reason) {
                $subtitle .= ' · ' . $b->cancel_reason;
            }
            $items[] = [
                'id'         => 'booking-cancelled-' . $b->id,
                'type'       => 'booking_cancelled',
                'title'      => 'Booking dibatalkan: ' . ($b->customer_name ?: 'Customer'),
                'subtitle'   => $subtitle,
                'url'        => route('admin.bookings.show', $b->id),
                'icon'       => 'x-circle',
                'tone'       => 'danger',
                'time_ago'   => $b->cancelled_at ? $b->cancelled_at->diffForHumans() : '',
                'created_at' => $b->cancelled_at ? $b->cancelled_at->toIso8601String() : now()->toIso8601String(),
            ];
        }

        // ===== 3. CONTACT MESSAGES UNREAD =====
        $messages = ContactMessage::where('status', 'unread')
            ->latest('created_at')
            ->take(20)
            ->get();

        foreach ($messages as $m) {
            $items[] = [
                'id'         => 'message-' . $m->id,
                'type'       => 'contact_message',
                'title'      => 'Pesan dari ' . ($m->name ?: 'Visitor'),
                'subtitle'   => $m->subject ?: '(No Subject)',
                'url'        => route('admin.contact-messages.show', $m->id),
                'icon'       => 'mail',
                'tone'       => 'warning',
                'time_ago'   => $m->created_at ? $m->created_at->diffForHumans() : '',
                'created_at' => $m->created_at ? $m->created_at->toIso8601String() : now()->toIso8601String(),
            ];
        }

        // ===== 4. NEWSLETTER SUBSCRIBERS (7 hari terakhir) =====
        $subs = NewsletterSubscriber::where('is_active', 1)
            ->where('subscribed_at', '>=', Carbon::now()->subDays(7))
            ->latest('subscribed_at')
            ->take(20)
            ->get();

        foreach ($subs as $s) {
            $items[] = [
                'id'         => 'subscriber-' . $s->id,
                'type'       => 'newsletter_subscriber',
                'title'      => 'Subscriber baru',
                'subtitle'   => $s->email,
                'url'        => route('admin.newsletters.index'),
                'icon'       => 'user-plus',
                'tone'       => 'success',
                'time_ago'   => $s->subscribed_at ? $s->subscribed_at->diffForHumans() : '',
                'created_at' => $s->subscribed_at ? $s->subscribed_at->toIso8601String() : now()->toIso8601String(),
            ];
        }

        // ===== SORT: created_at DESC =====
        usort($items, function ($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        $items = array_slice($items, 0, 30);

        return [
            'items' => array_values($items),
            'total' => count($items),
        ];
    }

    private function formatDate($date): string
    {
        if (!$date) return '-';
        try {
            return Carbon::parse($date)->format('d M Y');
        } catch (\Exception $e) {
            return '-';
        }
    }
}