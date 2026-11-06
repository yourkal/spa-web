<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapist extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'speciality',
        'experience',
        'rating',
        'phone',
        'avatar',
        'status',
        'is_active',
    ];

    protected $casts = [
        'rating'    => 'decimal:1',
        'is_active' => 'boolean',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'ready'     => 'Ready',
            'not_ready' => 'Belum Ready',
            'serving'   => 'Sedang Melayani',
            'off'       => 'Off',
            default     => 'Ready',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'ready'     => 'badge-ready',
            'not_ready' => 'badge-not-ready',
            'serving'   => 'badge-busy',
            'off'       => 'badge-off',
            default     => 'badge-ready',
        };
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activeSessions()
    {
        return $this->hasManyThrough(ActiveSession::class, Booking::class);
    }
}
