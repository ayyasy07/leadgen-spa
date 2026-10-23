<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'duration')) {
                $table->integer('duration')->nullable()->after('unit');
            }
            if (!Schema::hasColumn('packages', 'original_price')) {
                $table->integer('original_price')->nullable()->after('price');
            }
            if (!Schema::hasColumn('packages', 'saving')) {
                $table->integer('saving')->nullable()->after('original_price');
            }
            if (!Schema::hasColumn('packages', 'saving_percent')) {
                $table->integer('saving_percent')->nullable()->after('saving');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'duration',
                'original_price',
                'saving',
                'saving_percent',
            ]);
        });
    }
};