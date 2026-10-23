<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('galleries', 'is_featured')) {
                $table->boolean('is_featured')->default(0)->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (Schema::hasColumn('galleries', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};