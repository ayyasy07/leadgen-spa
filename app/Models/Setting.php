<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    /* =====================
       HELPERS
    ===================== */

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Ambil slot overrides sebagai array.
     * Format: {"10:00": 10, "14:00": 0}
     */
    public static function slotOverrides(): array
    {
        $json = self::get('booking_slot_overrides', '{}');
        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Simpan slot overrides dari array.
     */
    public static function setSlotOverrides(array $overrides): void
    {
        // Filter: value numeric, key format H:i
        $clean = [];
        foreach ($overrides as $time => $cap) {
            if (preg_match('/^\d{2}:\d{2}$/', $time) && is_numeric($cap)) {
                $clean[$time] = (int) $cap;
            }
        }
        self::set('booking_slot_overrides', json_encode($clean));
    }
}