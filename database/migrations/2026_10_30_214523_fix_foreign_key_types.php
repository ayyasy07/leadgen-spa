<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Matikan cek FK sementara
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Fix tabel bookings (tambahin kolom lain kalau ada yang error)
        DB::statement('ALTER TABLE bookings MODIFY COLUMN user_id INT UNSIGNED NULL');
        
        // Fix tabel personal_access_tokens (buat login/Sanctum)
        DB::statement('ALTER TABLE personal_access_tokens MODIFY COLUMN tokenable_id INT UNSIGNED NOT NULL');

        // Nyalain lagi cek FK
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down(): void
    {
        // Gak usah diapa-apain kalau di-rollback
    }
};