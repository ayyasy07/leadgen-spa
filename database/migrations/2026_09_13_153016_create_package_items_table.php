<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('package_id')
                  ->constrained('packages')
                  ->cascadeOnDelete();

            $table->foreignId('treatment_id')
                  ->nullable()
                  ->constrained('treatments')
                  ->nullOnDelete();

            $table->string('name');
            $table->integer('duration');
            $table->integer('price');
            $table->boolean('is_custom')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_items');
    }
};