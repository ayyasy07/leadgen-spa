<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $source = $model->slugSource ?? 'name';
            $value  = $model->{$source};

            if (empty($value)) return;

            if (empty($model->slug) || $model->isDirty($source)) {
                $model->slug = static::generateUniqueSlug($value, $model->id ?? null);
            }

            if (empty($model->meta_title)) {
                $model->meta_title = $value;
            }
        });
    }

    public static function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $i    = 1;

        while (static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}