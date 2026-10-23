<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'category',
        'unit',
        'description',
        'is_active',
        'is_featured',
        'image',
        'image_2',
        'image_3',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected $slugSource = 'name';

    public function prices()
    {
        return $this->hasMany(TreatmentPrice::class);
    }

    /**
     * Accessor: kumpulin image_1, image_2, image_3 jadi array
     * Dipake di treatment-detail.blade.php
     */
    public function getImagesAttribute(): array
    {
        return collect([
            $this->image,
            $this->image_2,
            $this->image_3,
        ])->filter()->values()->all();
    }

    /**
     * Ambil treatment alternatif dari kategori lain
     * buat gambar kedua di homepage.
     */
    public static function getAlternativeWithImage($excludeId, $category)
    {
        return static::where('is_active', 1)
            ->where('id', '!=', $excludeId)
            ->whereNotNull('image')
            ->where('category', $category)
            ->inRandomOrder()
            ->first()
            ?? static::where('is_active', 1)
                ->where('id', '!=', $excludeId)
                ->whereNotNull('image')
                ->inRandomOrder()
                ->first();
    }
}