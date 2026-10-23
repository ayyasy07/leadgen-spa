<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus setting lama
        DB::table('settings')->whereIn('key', [
            'working_hours_weekday',
            'working_hours_weekend',
        ])->delete();

        // Insert setting baru
        $now = now();
        $newSettings = [
            ['key' => 'weekday_label', 'value' => 'Mon - Sat', 'group' => 'working_hours', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'weekday_open',  'value' => '09:00',     'group' => 'working_hours', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'weekday_close', 'value' => '21:00',     'group' => 'working_hours', 'created_at' => $now, 'updated_at' => $now],

            ['key' => 'weekend_label', 'value' => 'Sunday',    'group' => 'working_hours', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'weekend_open',  'value' => '09:00',     'group' => 'working_hours', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'weekend_close', 'value' => '19:00',     'group' => 'working_hours', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('settings')->insert($newSettings);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'weekday_label', 'weekday_open', 'weekday_close',
            'weekend_label', 'weekend_open', 'weekend_close',
        ])->delete();
    }
};