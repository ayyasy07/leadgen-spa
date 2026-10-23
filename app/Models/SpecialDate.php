<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SpecialDate extends Model
{
    protected $fillable = [
        'start_date', 'end_date', 'type',
        'open_time', 'close_time', 'notes', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /* =====================
       SCOPES
    ===================== */

    public function scopeClosed($q)
    {
        return $q->where('type', 'closed');
    }

    public function scopeCustomHours($q)
    {
        return $q->where('type', 'custom_hours');
    }

    /**
     * Cek apakah tanggal tertentu masuk range special date.
     */
    public static function findForDate(string $date): ?self
    {
        $date = Carbon::parse($date)->format('Y-m-d');

        return self::where('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $date);
            })
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Cek apakah tanggal libur.
     */
    public static function isClosed(string $date): bool
    {
        $found = self::findForDate($date);
        return $found && $found->type === 'closed';
    }

    /**
     * Ambil custom hours kalau ada.
     * Return ['open' => 'HH:MM', 'close' => 'HH:MM'] atau null.
     */
    public static function getCustomHours(string $date): ?array
    {
        $found = self::findForDate($date);
        if ($found && $found->type === 'custom_hours' && $found->open_time && $found->close_time) {
            return [
                'open'  => substr($found->open_time, 0, 5),
                'close' => substr($found->close_time, 0, 5),
            ];
        }
        return null;
    }

    /**
     * Ambil semua special dates dalam range (buat render kalender).
     */
    public static function inRange(string $start, string $end)
    {
        return self::where(function ($q) use ($start, $end) {
                // start_date di range
                $q->whereBetween('start_date', [$start, $end])
                  // atau end_date di range
                  ->orWhereBetween('end_date', [$start, $end])
                  // atau range-nya nyakup start & end
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start)
                         ->where('end_date', '>=', $end);
                  });
            })
            ->orderBy('start_date')
            ->get();
    }
}