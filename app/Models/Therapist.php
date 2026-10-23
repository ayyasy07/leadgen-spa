<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Therapist extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'role',
        'photo',
        'bio',
        'sort_order',
        'is_active',
        'show_on_about',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'show_on_about' => 'boolean',
        'sort_order'    => 'integer',
    ];

    protected $slugSource = 'name';

    /**
     * Scope: cuma therapist yang tampil di halaman About.
     */
    public function scopeForAbout($query)
    {
        return $query->where('is_active', 1)
            ->where('show_on_about', 1)
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}