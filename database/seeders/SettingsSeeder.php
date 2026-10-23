<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Contact Info
            ['key' => 'contact_address', 'value' => '301 The Greenhouse, Custard Factory, London, E2 8DY', 'group' => 'contact'],
            ['key' => 'contact_phone',   'value' => '+44 (0) 123 456 7890',                                'group' => 'contact'],
            ['key' => 'contact_email',   'value' => 'hello@leadgenspa.com',                                'group' => 'contact'],
            ['key' => 'map_embed_url',   'value' => 'https://maps.google.com/maps?q=301%20The%20Greenhouse%2C%20Custard%20Factory%2C%20London%2C%20E2%208DY&t=&z=15&ie=UTF8&iwloc=&output=embed', 'group' => 'contact'],

            // Working Hours
            ['key' => 'weekday_label',   'value' => 'Mon - Sat',   'group' => 'working_hours'],
            ['key' => 'weekday_open',    'value' => '09:00',       'group' => 'working_hours'],
            ['key' => 'weekday_close',   'value' => '21:00',       'group' => 'working_hours'],
            ['key' => 'weekend_label',   'value' => 'Sunday',      'group' => 'working_hours'],
            ['key' => 'weekend_open',    'value' => '09:00',       'group' => 'working_hours'],
            ['key' => 'weekend_close',   'value' => '19:00',       'group' => 'working_hours'],

            // Social Media
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/leadgenspa',      'group' => 'social'],
            ['key' => 'social_whatsapp',  'value' => 'https://wa.me/6281234567890',            'group' => 'social'],
            ['key' => 'social_facebook',  'value' => 'https://facebook.com/leadgenspa',        'group' => 'social'],
            ['key' => 'social_twitter',   'value' => 'https://twitter.com/leadgenspa',         'group' => 'social'],
            ['key' => 'social_linkedin',  'value' => 'https://linkedin.com/company/leadgenspa', 'group' => 'social'],
        ];

        foreach ($settings as $s) {
            DB::table('settings')->updateOrInsert(
                ['key' => $s['key']],
                array_merge($s, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}