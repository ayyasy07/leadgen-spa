<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_dates', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('type', ['closed', 'custom_hours'])->default('closed');
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('start_date');
            $table->index('end_date');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_dates');
    }
};