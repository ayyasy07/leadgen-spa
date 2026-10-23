<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();

            $table->string('name');            // nama customer
            $table->string('role')->nullable(); // jabatan / keterangan (opsional)
            $table->integer('rating')->default(5); // 1–5
            $table->text('message');           // isi testimoni
            $table->string('photo')->nullable(); // foto customer (opsional)
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};