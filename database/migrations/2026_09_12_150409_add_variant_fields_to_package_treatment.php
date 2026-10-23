<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_treatment', function (Blueprint $table) {
            if (!Schema::hasColumn('package_treatment', 'duration')) {
                $table->integer('duration')->nullable()->after('treatment_id');
            }
            if (!Schema::hasColumn('package_treatment', 'price')) {
                $table->integer('price')->nullable()->after('duration');
            }
        });
    }

    public function down(): void
    {
        Schema::table('package_treatment', function (Blueprint $table) {
            $table->dropColumn(['duration', 'price']);
        });
    }
};