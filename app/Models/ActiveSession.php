<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_name',
        'service_name',
        'therapist_name',
        'started_at',
        'total_duration_minutes',
        'remaining_seconds',
        'last_synced_at',
        'status',
        'ended_at',
        'is_paused',
    ];

    protected $casts = [
        'started_at'     => 'datetime',
        'ended_at'       => 'datetime',
        'last_synced_at' => 'datetime',
        'is_paused'      => 'boolean',
    ];

    public function syncRemaining(): int
    {
        if ($this->status !== 'running') {
            return (int) $this->remaining_seconds;
        }

        if ((int) $this->remaining_seconds <= 0) {
            $this->update([
                'remaining_seconds' => 0,
                'status'            => 'ended',
                'ended_at'          => $this->ended_at ?? now(),
                'last_synced_at'    => now(),
            ]);
            if ($booking = $this->booking) {
                $booking->update(['status' => 'completed']);
                if ($booking->therapist_id) {
                    Therapist::where('id', $booking->therapist_id)
                        ->where('status', 'serving')
                        ->update(['status' => 'ready']);
                }
            }
            return 0;
        }

        if ($this->is_paused) {
            $this->update(['last_synced_at' => now()]);
            return (int) $this->remaining_seconds;
        }

        $reference = \Illuminate\Support\Carbon::parse($this->last_synced_at ?? $this->started_at ?? now());
        $passed = (int) $reference->diffInSeconds(now());
        if ($passed > 0) {
            $newRemaining = max(0, (int) $this->remaining_seconds - $passed);
            $this->update([
                'remaining_seconds' => $newRemaining,
                'last_synced_at'    => now(),
            ]);

            if ($newRemaining === 0) {
                $this->update([
                    'status'   => 'ended',
                    'ended_at' => now(),
                ]);
                if ($booking = $this->booking) {
                    $booking->update(['status' => 'completed']);
                    if ($booking->therapist_id) {
                        Therapist::where('id', $booking->therapist_id)
                            ->where('status', 'serving')
                            ->update(['status' => 'ready']);
                    }
                }
            }

            return $newRemaining;
        }

        return (int) $this->remaining_seconds;
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
