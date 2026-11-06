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
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->foreignId('spa_service_id')->constrained('spa_services')->cascadeOnDelete();
            $table->string('service_name');
            $table->foreignId('therapist_id')->nullable()->constrained('therapists')->nullOnDelete();
            $table->string('therapist_name')->nullable();
            $table->date('booking_date');
            $table->string('booking_time'); // e.g. "14:00"
            $table->integer('duration'); // duration in minutes
            $table->decimal('price', 12, 2);
            $table->string('payment_method')->default('QRIS / E-Wallet');
            $table->string('status')->default('confirmed'); // confirmed, in_progress, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
