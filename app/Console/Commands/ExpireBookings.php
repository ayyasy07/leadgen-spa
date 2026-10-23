<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature   = 'bookings:expire';
    protected $description = 'Auto-expire pending bookings yang udah lewat batas waktu';

    public function handle(): void
    {
        $minutes = (float) Setting::get('booking_pending_timeout', 0.25) * 60;

        $count = Booking::where('status', Booking::STATUS_PENDING)
            ->where('created_at', '<', now()->subMinutes($minutes))
            ->update(['status' => Booking::STATUS_EXPIRED]);

        $this->info("Expired: {$count} booking(s)");
    }
}