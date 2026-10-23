<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_code', 'user_id', 'customer_name', 'customer_phone',
        'customer_email', 'booking_date', 'booking_time', 'items',
        'total_price', 'refund_percent', 'net_revenue',
        'cancelled_at', 'cancel_reason', 'notes', 'status',
        // Payment fields (Duitku)
        'payment_reference', 'payment_method', 'payment_method_name',
        'payment_url', 'payment_fee', 'payment_amount',
        'payment_status', 'paid_at', 'expired_at',
    ];

    protected $casts = [
        'items'          => 'array',
        'booking_date'   => 'date',
        'total_price'    => 'integer',
        'refund_percent' => 'integer',
        'net_revenue'    => 'integer',
        'payment_fee'    => 'integer',
        'payment_amount' => 'integer',
        'cancelled_at'   => 'datetime',
        'paid_at'        => 'datetime',
        'expired_at'     => 'datetime',
        'created_at'     => 'datetime',
    ];

    /* =====================
       STATUS CONSTANTS
    ===================== */
    const STATUS_PENDING   = 'pending';
    const STATUS_PAID      = 'paid';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW   = 'no-show';
    const STATUS_EXPIRED   = 'expired';

    /* =====================
       PAYMENT STATUS
    ===================== */
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_SUCCESS = 'success';
    const PAYMENT_FAILED  = 'failed';
    const PAYMENT_EXPIRED = 'expired';

    /* =====================
       GENERATE CODE
    ===================== */
    public static function generateCode(): string
    {
        $prefix = 'BKG-' . now()->format('ymd') . '-';
        do {
            $suffix = strtoupper(Str::random(4));
            $code   = $prefix . $suffix;
        } while (self::where('booking_code', $code)->withTrashed()->exists());
        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== 'all') {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_PAID]);
    }

    /* =====================
       WHATSAPP
    ===================== */
    public function getWhatsappUrlAttribute(): string
    {
        $phone = $this->normalizePhone($this->customer_phone ?? '');
        if (empty($phone)) return '#';
        $name  = $this->customer_name ?? 'Customer';
        $code  = $this->booking_code ?? '-';
        $message = "Hello {$name}, this is the LeadGen Spa team. We'd like to confirm your booking with code {$code}. Is there anything we can help you with?";
        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }

    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($phone)) return '';
        if (substr($phone, 0, 1) === '0') return '62' . substr($phone, 1);
        if (substr($phone, 0, 1) === '8') return '62' . $phone;
        return $phone;
    }

    public static function normalizePhoneStatic(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($phone)) return '';
        if (substr($phone, 0, 1) === '0') return '62' . substr($phone, 1);
        if (substr($phone, 0, 1) === '8') return '62' . $phone;
        return $phone;
    }

    /* =====================
       DOUBLE BOOKING
    ===================== */
    public static function hasUserConflict($userId, $phone, $date, $time): bool
    {
        $normalizedPhone = self::normalizePhoneStatic($phone);

        return self::where('booking_date', $date)
            ->where('booking_time', $time)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PAID])
            ->where(function ($q) use ($userId, $normalizedPhone) {
                if ($userId) {
                    $q->orWhere('user_id', $userId);
                }
                if ($normalizedPhone) {
                    $q->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(customer_phone, ' ', ''), '-', ''), '.', '') = ?",
                        [$normalizedPhone]
                    );
                }
            })
            ->exists();
    }

    /* =====================
       REVENUE CALCULATION
    ===================== */
    public function calculateNetRevenue(): int
    {
        $price = (int) $this->total_price;
        switch ($this->status) {
            case self::STATUS_PAID:
            case self::STATUS_COMPLETED:
            case self::STATUS_NO_SHOW:
                return $price;
            case self::STATUS_CANCELLED:
                $refund = max(0, min(100, (int) $this->refund_percent));
                return (int) round($price * (100 - $refund) / 100);
            case self::STATUS_PENDING:
            case self::STATUS_EXPIRED:
            default:
                return 0;
        }
    }

    public function calculateAutoRefund(): int
    {
        if (!$this->booking_date || !$this->booking_time) return 0;
        $treatmentAt = Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->booking_time);
        $cancelAt    = $this->cancelled_at ?? now();
        $diffHours   = $cancelAt->diffInHours($treatmentAt, false);
        return $diffHours > 48 ? 50 : 0;
    }

    /* =====================
       STATUS CHECKS
    ===================== */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isExpired(): bool
    {
        if ($this->status !== self::STATUS_PENDING) return false;
        if ($this->expired_at) {
            return $this->expired_at->isPast();
        }
        // Fallback: hitung dari created_at + timeout setting
        $minutes = (float) Setting::get('booking_pending_timeout', 15);
        return $this->created_at->addMinutes($minutes)->isPast();
    }

    public function isLocked(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PAID,
        ]);
    }

    public function canBePaid(): bool
    {
        if ($this->status !== self::STATUS_PENDING) return false;
        if (empty($this->payment_url)) return false;
        return !$this->isExpired();
    }

    public function requiresAdminApprovalForCancel(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /* =====================
       SLOT HOLDING
    ===================== */
    public function isHoldingSlot(): bool
    {
        if ($this->status === self::STATUS_PAID) return true;
        if ($this->status === self::STATUS_PENDING) {
            $minutes = (float) Setting::get('booking_pending_timeout', 15);
            return $this->created_at->addMinutes($minutes)->isFuture();
        }
        return false;
    }

    /* =====================
       BOOTED — AUTO CALC
    ===================== */
    protected static function booted()
    {
        static::saving(function ($booking) {
            // Auto-set cancelled_at
            if ($booking->status === self::STATUS_CANCELLED
                && !$booking->cancelled_at
                && $booking->isDirty('status')) {
                $booking->cancelled_at = now();
            }
            // Auto-calc refund percent kalo cancel
            if ($booking->status === self::STATUS_CANCELLED
                && $booking->isDirty('status')
                && $booking->refund_percent === 0) {
                $booking->refund_percent = $booking->calculateAutoRefund();
            }
            // Auto-set paid_at
            if ($booking->status === self::STATUS_PAID
                && !$booking->paid_at
                && $booking->isDirty('status')) {
                $booking->paid_at = now();
            }
            $booking->net_revenue = $booking->calculateNetRevenue();
        });
    }

    /* =====================
       SLOT COUNT
    ===================== */
    public function countSlots(): int
    {
        $slots = 0;
        foreach (($this->items ?? []) as $item) {
            $unit = $item['unit'] ?? 'person';
            $qty  = (int) ($item['qty'] ?? 1);
            $slots += ($unit === 'couple') ? ($qty * 2) : $qty;
        }
        return $slots;
    }

    /* =====================
       STATIC: SLOT CALCULATION
    ===================== */
    private static function normalizeTime($value, string $fallback): string
    {
        if (empty($value) || !is_string($value)) return $fallback;
        $value = trim(str_replace('.', ':', $value));
        if (preg_match('/^(\d{1,2}):(\d{2})$/', $value, $m)) {
            $h = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            return "{$h}:{$m[2]}";
        }
        return $fallback;
    }

    public static function getOperatingHours($date): array
    {
        $dateStr = Carbon::parse($date)->format('Y-m-d');

        if (class_exists(\App\Models\SpecialDate::class)) {
            if (\App\Models\SpecialDate::isClosed($dateStr)) {
                return [];
            }
            $custom = \App\Models\SpecialDate::getCustomHours($dateStr);
            if ($custom) {
                return self::generateSlots($custom['open'], $custom['close']);
            }
        }

        $dateCarbon = Carbon::parse($date);
        $isWeekend  = $dateCarbon->isWeekend();
        $openRaw    = Setting::get($isWeekend ? 'weekend_open'  : 'weekday_open');
        $closeRaw   = Setting::get($isWeekend ? 'weekend_close' : 'weekday_close');
        $open       = self::normalizeTime($openRaw,  $isWeekend ? '10:00' : '10:00');
        $close      = self::normalizeTime($closeRaw, $isWeekend ? '20:00' : '19:00');

        return self::generateSlots($open, $close);
    }

    private static function generateSlots(string $open, string $close): array
    {
        try {
            $o = Carbon::createFromFormat('H:i', $open);
            $c = Carbon::createFromFormat('H:i', $close);
        } catch (\Exception $e) {
            $o = Carbon::createFromFormat('H:i', '10:00');
            $c = Carbon::createFromFormat('H:i', '19:00');
        }
        if ($c->lte($o)) {
            $o = Carbon::createFromFormat('H:i', '10:00');
            $c = Carbon::createFromFormat('H:i', '19:00');
        }
        $slots  = [];
        $cursor = $o->copy();
        while ($cursor->lt($c)) {
            $slots[] = $cursor->format('H:i');
            $cursor->addHour();
        }
        return $slots;
    }

    public static function getSlotCapacity($time): int
    {
        $default   = (int) Setting::get('booking_default_capacity', 8);
        $overrides = Setting::slotOverrides();
        return isset($overrides[$time]) ? (int) $overrides[$time] : $default;
    }

    public static function getBookedSlots($date, $time): int
    {
        $minutes          = (float) Setting::get('booking_pending_timeout', 15);
        $pendingThreshold = now()->subMinutes($minutes);

        $bookings = self::where('booking_date', $date)
            ->where('booking_time', $time)
            ->where(function ($q) use ($pendingThreshold) {
                $q->where('status', self::STATUS_PAID)
                  ->orWhere(function ($q2) use ($pendingThreshold) {
                      $q2->where('status', self::STATUS_PENDING)
                         ->where('created_at', '>=', $pendingThreshold);
                  });
            })
            ->get();

        return $bookings->sum(fn($b) => $b->countSlots());
    }

    public static function getRemainingSlots($date, $time): int
    {
        return max(0, self::getSlotCapacity($time) - self::getBookedSlots($date, $time));
    }

    public static function isSlotDisabled($time): bool
    {
        return self::getSlotCapacity($time) === 0;
    }

    /* =====================
       TIME LOCK
    ===================== */
    public static function getBufferMinutes(): int
    {
        $val = (int) Setting::get('booking_buffer_minutes', 0);
        return max(0, min(120, $val));
    }

    public static function getMaxAdvanceMonths(): int
    {
        $val = (int) Setting::get('booking_max_advance_months', 2);
        return max(1, min(12, $val));
    }

    public static function getMaxBookingDate(): string
    {
        return now()->addMonths(self::getMaxAdvanceMonths())->format('Y-m-d');
    }

    public static function getMinBookableTime(): Carbon
    {
        return now()->addMinutes(self::getBufferMinutes());
    }

    public static function isTimeLocked($date, $time): bool
    {
        $dateStr = Carbon::parse($date)->format('Y-m-d');
        $today   = now()->format('Y-m-d');

        if ($dateStr < $today) return true;
        if ($dateStr !== $today) return false;

        try {
            $slotAt = Carbon::parse($dateStr . ' ' . $time);
        } catch (\Exception $e) {
            return true;
        }

        return $slotAt->lte(self::getMinBookableTime());
    }

    /* =====================
       LABEL STATUS
    ===================== */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING   => 'Pending Payment',
            self::STATUS_PAID      => 'Paid',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_NO_SHOW   => 'No-Show',
            self::STATUS_EXPIRED   => 'Expired',
            default                => ucfirst($this->status),
        };
    }
}