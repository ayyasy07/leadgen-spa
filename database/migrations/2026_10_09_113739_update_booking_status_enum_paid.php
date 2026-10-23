<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Rename status 'confirmed' → 'paid' di semua row existing
        DB::table('bookings')
            ->where('status', 'confirmed')
            ->update(['status' => 'paid']);
    }

    public function down(): void
    {
        // Balikin 'paid' → 'confirmed'
        DB::table('bookings')
            ->where('status', 'paid')
            ->update(['status' => 'confirmed']);
    }
};