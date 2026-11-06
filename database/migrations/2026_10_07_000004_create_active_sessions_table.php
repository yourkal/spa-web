<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('active_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('service_name')->nullable();
            $table->string('therapist_name')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->integer('total_duration_minutes')->default(60);
            $table->integer('remaining_seconds')->default(3600);
            $table->string('status')->default('running'); // running, paused, finished
            $table->boolean('is_paused')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_sessions');
    }
};
