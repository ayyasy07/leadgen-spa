<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Seed default categories
        $defaults = ['interior', 'treatments', 'atmosphere'];
        foreach ($defaults as $i => $name) {
            DB::table('gallery_categories')->insert([
                'name'       => ucfirst($name),
                'slug'       => $name,
                'sort_order' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Tambah kolom category_id
        Schema::table('galleries', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('image')
                  ->constrained('gallery_categories')
                  ->nullOnDelete();
        });

        // 3. Drop kolom category lama (kalau ada)
        if (Schema::hasColumn('galleries', 'category')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });

        Schema::dropIfExists('gallery_categories');
    }
};