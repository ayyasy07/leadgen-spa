<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            if (Schema::hasColumn('treatments', 'duration')) {
                $table->dropColumn('duration');
            }
            if (Schema::hasColumn('treatments', 'price')) {
                $table->dropColumn('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->integer('duration')->nullable();
            $table->integer('price')->nullable();
        });
    }
};