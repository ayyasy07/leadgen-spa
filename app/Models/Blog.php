<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'category_id',
        'image',
        'excerpt',
        'content',
        'is_published',
        'published_at',
        'sort_order',
         'direction',
        'text_align',
        'line_height',
        'caption',
        'gallery',
        'keywords',
        'author_name',
        'show_author',
        'allow_comments',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'sort_order'   => 'integer',
        'gallery'        => 'array',
        'show_author'    => 'boolean',    
        'allow_comments' => 'boolean', 
    ];

    protected $slugSource = 'title';

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}