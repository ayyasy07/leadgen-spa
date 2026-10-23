<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            'booking_default_capacity'    => '8',
            'booking_pending_timeout'     => '5',
            'booking_slot_overrides'      => json_encode([
                // contoh: "10:00": 10, "14:00": 0
            ]),
        ];

        foreach ($defaults as $key => $value) {
            $exists = DB::table('settings')->where('key', $key)->exists();
            if (!$exists) {
                DB::table('settings')->insert([
                    'key'        => $key,
                    'value'      => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'booking_default_capacity',
            'booking_pending_timeout',
            'booking_slot_overrides',
        ])->delete();
    }
};