<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Package;
use App\Models\Therapist;
use App\Models\Treatment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BackfillSlugs extends Command
{
    protected $signature   = 'slugs:backfill';
    protected $description = 'Backfill slug + meta_title untuk data lama';

    public function handle(): void
    {
        $this->backfill(Treatment::class, 'name');
        $this->backfill(Package::class,   'name');
        $this->backfill(Blog::class,      'title');
        $this->backfill(Therapist::class, 'name');

        $this->info('✅ Backfill selesai.');
    }

    protected function backfill(string $model, string $source): void
    {
        $count = 0;

        $model::withTrashed()->orderBy('id')->chunk(100, function ($rows) use ($model, $source, &$count) {
            foreach ($rows as $row) {
                $base = Str::slug($row->{$source});
                $slug = $base;
                $i    = 1;

                while ($model::withTrashed()
                    ->where('slug', $slug)
                    ->where('id', '!=', $row->id)
                    ->exists()
                ) {
                    $slug = "{$base}-{$i}";
                    $i++;
                }

                $row->slug = $slug;

                if (empty($row->meta_title)) {
                    $row->meta_title = $row->{$source};
                }

                $row->saveQuietly();
                $count++;
            }
        });

        $this->line("→ {$model}: {$count} row diupdate");
    }
}