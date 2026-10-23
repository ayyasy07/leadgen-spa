<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'date', 'time', 'title', 'description', 'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function scopeForDate($q, string $date)
    {
        return $q->whereDate('date', $date);
    }

    public function scopeInMonth($q, int $year, int $month)
    {
        return $q->whereYear('date', $year)->whereMonth('date', $month);
    }
}