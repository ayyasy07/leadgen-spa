<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'description',
        'price',
        'unit',
        'image',
        'is_active',
        'is_featured',
        'is_popular',
        'featured_at',
        'duration',
        'original_price',
        'saving',
        'saving_percent',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
        'is_popular'     => 'boolean',
        'featured_at'    => 'datetime',
        'duration'       => 'integer',
        'price'          => 'integer',
        'original_price' => 'integer',
        'saving'         => 'integer',
        'saving_percent' => 'integer',
    ];

    protected $slugSource = 'name';

    /**
     * Scope: featured packages (urutan FIFO — paling lama dulu)
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1)
                     ->orderBy('featured_at');
    }

    /**
     * Scope: active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function items()
    {
        return $this->hasMany(PackageItem::class)->orderBy('id');
    }

    public function treatments()
    {
        return $this->belongsToMany(Treatment::class)
                    ->withPivot(['duration', 'price'])
                    ->withTimestamps();
    }
}