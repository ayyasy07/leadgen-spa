<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    protected $fillable = [
        'package_id',
        'treatment_id',
        'name',
        'duration',
        'price',
        'is_custom',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'duration'  => 'integer',
        'price'     => 'integer',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}