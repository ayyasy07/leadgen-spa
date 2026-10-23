<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ganti ENUM → VARCHAR (fleksibel, gak bakal Data truncated)
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status VARCHAR(30) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Balikin ke ENUM (kalo perlu)
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'no-show', 'expired') NOT NULL DEFAULT 'pending'");
    }
};