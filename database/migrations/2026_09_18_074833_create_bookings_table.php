<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->string('booking_code')->unique();

            // Customer
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();

            // Schedule
            $table->date('booking_date');
            $table->string('booking_time');

            // Items (JSON)
            $table->json('items'); // [{name, duration, qty, price, unit}]
            $table->integer('total_price')->default(0);

            // Notes & status
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no-show'])
                  ->default('pending');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('booking_date');
            $table->index('customer_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};