<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'spa_service_id',
        'service_name',
        'therapist_id',
        'therapist_name',
        'booking_date',
        'booking_time',
        'duration',
        'price',
        'payment_method',
        'status',
        'notes',
        'is_new',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'price' => 'decimal:2',
        'is_new' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(SpaService::class, 'spa_service_id');
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class);
    }

    public function activeSession()
    {
        return $this->hasOne(ActiveSession::class);
    }

    public function formattedPrice(): string
    {
        return 'AED ' . number_format($this->price, 2);
    }
}
