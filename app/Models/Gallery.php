<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'category_id',
        'sort_order',
        'is_active',
        'is_featured',
        'featured_at',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
        'featured_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
    }

    /**
     * Scope: cuma gallery featured + active (buat homepage).
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1)
            ->where('is_active', 1);
    }

    /**
     * Scope: FIFO featured (paling lama dulu).
     */
    public function scopeFeaturedFifo($query)
    {
        return $query->where('is_featured', 1)->orderBy('featured_at');
    }

    /**
     * Max featured dari config.
     */
    public static function maxFeatured(): int
    {
        return (int) config('spa.gallery_max_featured', 10);
    }
}