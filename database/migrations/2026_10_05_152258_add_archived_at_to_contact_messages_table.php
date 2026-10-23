<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('replied_at');
            }
        });

        // Migrate data lama: status 'archived' → archived_at, status jadi 'read'
        DB::table('contact_messages')
            ->where('status', 'archived')
            ->update([
                'archived_at' => DB::raw('updated_at'),
                'status'      => 'read',
            ]);
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
    }
};