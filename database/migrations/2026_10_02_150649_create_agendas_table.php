<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->index(['date', 'time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};