<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('therapists', function (Blueprint $table) {
            if (!Schema::hasColumn('therapists', 'show_on_about')) {
                $table->boolean('show_on_about')->default(1)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('therapists', function (Blueprint $table) {
            if (Schema::hasColumn('therapists', 'show_on_about')) {
                $table->dropColumn('show_on_about');
            }
        });
    }
};