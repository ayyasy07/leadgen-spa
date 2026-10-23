<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentPrice extends Model
{
    protected $fillable = [
        'treatment_id',
        'duration',
        'price',
    ];

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}