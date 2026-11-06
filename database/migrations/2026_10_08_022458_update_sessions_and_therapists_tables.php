<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('active_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('active_sessions', 'ended_at')) {
                $table->timestamp('ended_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('active_sessions', 'last_synced_at')) {
                $table->timestamp('last_synced_at')->nullable()->after('remaining_seconds');
            }
        });

        Schema::table('therapists', function (Blueprint $table) {
            if (!Schema::hasColumn('therapists', 'status')) {
                $table->string('status')->default('ready')->after('avatar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('active_sessions', function (Blueprint $table) {
            $table->dropColumn(['ended_at', 'last_synced_at']);
        });

        Schema::table('therapists', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
