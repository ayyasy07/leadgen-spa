<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'refund_percent')) {
                $table->integer('refund_percent')->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('bookings', 'net_revenue')) {
                $table->integer('net_revenue')->default(0)->after('refund_percent');
            }
            if (!Schema::hasColumn('bookings', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('net_revenue');
            }
            if (!Schema::hasColumn('bookings', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('cancelled_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $cols = ['refund_percent', 'net_revenue', 'cancelled_at', 'cancel_reason'];
            $drop = [];
            foreach ($cols as $c) {
                if (Schema::hasColumn('bookings', $c)) $drop[] = $c;
            }
            if (!empty($drop)) $table->dropColumn($drop);
        });
    }
};