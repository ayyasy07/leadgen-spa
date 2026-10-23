<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'contact_address' => Setting::get('contact_address'),
            'contact_phone'   => Setting::get('contact_phone'),
            'contact_email'   => Setting::get('contact_email'),
            'map_embed_url'   => Setting::get('map_embed_url'),

            'weekday_label'   => Setting::get('weekday_label', 'Mon - Sat'),
            'weekday_open'    => Setting::get('weekday_open', '10:00'),
            'weekday_close'   => Setting::get('weekday_close', '19:00'),
            'weekend_label'   => Setting::get('weekend_label', 'Sunday'),
            'weekend_open'    => Setting::get('weekend_open', '10:00'),
            'weekend_close'   => Setting::get('weekend_close', '20:00'),

            'booking_default_capacity'     => Setting::get('booking_default_capacity', '8'),
            'booking_pending_timeout'      => Setting::get('booking_pending_timeout', '15'),
            'booking_buffer_minutes'       => Setting::get('booking_buffer_minutes', '0'),
            'booking_max_advance_months'   => Setting::get('booking_max_advance_months', '2'),
            'booking_slot_overrides'       => Setting::slotOverrides(),

            'social_instagram' => Setting::get('social_instagram'),
            'social_whatsapp'  => Setting::get('social_whatsapp'),
            'social_facebook'  => Setting::get('social_facebook'),
            'social_twitter'   => Setting::get('social_twitter'),
            'social_linkedin'  => Setting::get('social_linkedin'),
        ];

        $timeSlots = $this->getAllTimeSlots($settings);

        return view('pages.settings.index', [
            'settings'  => $settings,
            'timeSlots' => $timeSlots,
            'layout'    => 'side-menu',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'contact_address' => 'nullable|string|max:500',
            'contact_phone'   => 'nullable|string|max:100',
            'contact_email'   => 'nullable|email|max:255',
            'map_embed_url'   => 'nullable|string|max:1000',

            'weekday_label'   => 'nullable|string|max:100',
            'weekday_open'    => 'nullable|date_format:H:i',
            'weekday_close'   => 'nullable|date_format:H:i|after:weekday_open',
            'weekend_label'   => 'nullable|string|max:100',
            'weekend_open'    => 'nullable|date_format:H:i',
            'weekend_close'   => 'nullable|date_format:H:i|after:weekend_open',

            'booking_default_capacity'   => 'nullable|integer|min:1|max:100',
            'booking_pending_timeout'    => 'nullable|integer|min:1|max:4320',
            'booking_buffer_minutes'     => 'nullable|integer|min:0|max:120',
            'booking_max_advance_months' => 'nullable|integer|min:1|max:12',
            'booking_slot_overrides'     => 'nullable|array',
            'booking_slot_overrides.*.time'     => 'required|date_format:H:i',
            'booking_slot_overrides.*.capacity' => 'required|integer|min:0|max:100',

            'social_instagram' => 'nullable|url|max:255',
            'social_whatsapp'  => 'nullable|url|max:255',
            'social_facebook'  => 'nullable|url|max:255',
            'social_twitter'   => 'nullable|url|max:255',
            'social_linkedin'  => 'nullable|url|max:255',
        ], [
            'weekday_close.after' => 'Weekday close time must be after open time.',
            'weekend_close.after' => 'Weekend close time must be after open time.',
            'booking_pending_timeout.integer' => 'Pending timeout must be a whole number (minutes).',
            'booking_pending_timeout.min'     => 'Pending timeout must be at least 1 minute.',
            'booking_pending_timeout.max'     => 'Pending timeout max 4320 minutes (72 hours).',
            'booking_buffer_minutes.integer'  => 'Buffer window must be a whole number (minutes).',
            'booking_buffer_minutes.min'      => 'Buffer window cannot be negative.',
            'booking_buffer_minutes.max'      => 'Buffer window max 120 minutes.',
            'booking_max_advance_months.integer' => 'Max advance must be a whole number (months).',
            'booking_max_advance_months.min'     => 'Max advance min 1 month.',
            'booking_max_advance_months.max'     => 'Max advance max 12 months.',
        ]);

        $keys = [
            'contact_address', 'contact_phone', 'contact_email', 'map_embed_url',
            'weekday_label', 'weekday_open', 'weekday_close',
            'weekend_label', 'weekend_open', 'weekend_close',
            'booking_default_capacity', 'booking_pending_timeout',
            'booking_buffer_minutes', 'booking_max_advance_months',
            'social_instagram', 'social_whatsapp', 'social_facebook',
            'social_twitter', 'social_linkedin',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key));
        }

        $overrides = [];
        if ($request->has('booking_slot_overrides')) {
            foreach ($request->input('booking_slot_overrides') as $row) {
                if (!empty($row['time'])) {
                    $overrides[$row['time']] = (int) ($row['capacity'] ?? 0);
                }
            }
        }
        Setting::setSlotOverrides($overrides);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated.');
    }

    private function getAllTimeSlots(array $settings): array
    {
        $weekdayHours = Booking::getOperatingHours(now()->startOfWeek()->format('Y-m-d'));
        $weekendHours = Booking::getOperatingHours(now()->startOfWeek()->addDays(5)->format('Y-m-d'));

        $all = array_unique(array_merge($weekdayHours, $weekendHours));
        sort($all);

        return array_values($all);
    }
}