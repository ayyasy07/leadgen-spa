<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==================== TREATMENTS ====================
        Schema::table('treatments', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
        });

        // ==================== PACKAGES ====================
        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
        });

        // ==================== BLOGS ====================
        // (slug udah ada di tabel blogs, jadi cuma tambah meta)
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
        });

        // ==================== THERAPISTS ====================
        Schema::table('therapists', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropColumn(['slug', 'meta_title', 'meta_description']);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['slug', 'meta_title', 'meta_description']);
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });

        Schema::table('therapists', function (Blueprint $table) {
            $table->dropColumn(['slug', 'meta_title', 'meta_description']);
        });
    }
};