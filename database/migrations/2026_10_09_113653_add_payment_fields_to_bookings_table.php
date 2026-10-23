<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'payment_reference')) {
                $table->string('payment_reference', 100)->nullable()->after('net_revenue');
            }
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 20)->nullable()->after('payment_reference');
            }
            if (!Schema::hasColumn('bookings', 'payment_method_name')) {
                $table->string('payment_method_name', 100)->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('bookings', 'payment_url')) {
                $table->text('payment_url')->nullable()->after('payment_method_name');
            }
            if (!Schema::hasColumn('bookings', 'payment_fee')) {
                $table->integer('payment_fee')->default(0)->after('payment_url');
            }
            if (!Schema::hasColumn('bookings', 'payment_amount')) {
                $table->integer('payment_amount')->default(0)->after('payment_fee');
            }
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status', 20)->nullable()->after('payment_amount');
            }
            if (!Schema::hasColumn('bookings', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('bookings', 'expired_at')) {
                $table->timestamp('expired_at')->nullable()->after('paid_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $cols = [
                'payment_reference', 'payment_method', 'payment_method_name',
                'payment_url', 'payment_fee', 'payment_amount',
                'payment_status', 'paid_at', 'expired_at',
            ];
            $drop = [];
            foreach ($cols as $c) {
                if (Schema::hasColumn('bookings', $c)) $drop[] = $c;
            }
            if (!empty($drop)) $table->dropColumn($drop);
        });
    }
};