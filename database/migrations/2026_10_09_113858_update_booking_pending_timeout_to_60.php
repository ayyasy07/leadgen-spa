<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update timeout dari 5 menit → 60 menit
        DB::table('settings')
            ->where('key', 'booking_pending_timeout')
            ->update([
                'value'      => '60',
                'updated_at' => now(),
            ]);

        // Tambah setting baru: booking_expiry_period (buat Duitku expiryPeriod)
        $exists = DB::table('settings')->where('key', 'booking_expiry_period')->exists();
        if (!$exists) {
            DB::table('settings')->insert([
                'key'        => 'booking_expiry_period',
                'value'      => '60',
                'group'      => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Balikin ke 5 menit
        DB::table('settings')
            ->where('key', 'booking_pending_timeout')
            ->update([
                'value'      => '5',
                'updated_at' => now(),
            ]);

        // Hapus setting booking_expiry_period
        DB::table('settings')->where('key', 'booking_expiry_period')->delete();
    }
};