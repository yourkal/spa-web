<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaService extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'duration',
        'price',
        'pricing_tiers',
        'image_url',
        'rating',
        'benefits',
        'is_active',
    ];

    protected $casts = [
        'benefits' => 'array',
        'pricing_tiers' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function formattedPrice(): string
    {
        return 'AED ' . number_format($this->price, 2);
    }

    public function formattedDuration(): string
    {
        $h = intdiv($this->duration, 60);
        $m = $this->duration % 60;
        if ($h && $m) return "{$h} jam {$m} menit";
        if ($h) return "{$h} jam";
        return "{$m} menit";
    }

    /**
     * Calculate price for duration using defined pricing tiers, or standard scaling.
     */
    public function calculatePriceForDuration(int $duration): float
    {
        if ($duration <= 0) {
            return 0.0;
        }

        // 1. Direct tier match if defined
        if (!empty($this->pricing_tiers) && is_array($this->pricing_tiers)) {
            if (isset($this->pricing_tiers[$duration])) {
                return (float) $this->pricing_tiers[$duration];
            }
            if (isset($this->pricing_tiers[(string) $duration])) {
                return (float) $this->pricing_tiers[(string) $duration];
            }

            // If not found in tiers, check if we can interpolate or scale from tiers
            $tiers = [];
            foreach ($this->pricing_tiers as $d => $p) {
                $tiers[(int) $d] = (float) $p;
            }
            ksort($tiers);

            $durations = array_keys($tiers);
            $maxTierDuration = end($durations);
            $minTierDuration = reset($durations);

            if ($duration <= $minTierDuration && $minTierDuration > 0) {
                return round(($tiers[$minTierDuration] / $minTierDuration) * $duration, 2);
            }

            if ($duration > $maxTierDuration && $maxTierDuration > 0) {
                // Rate based on 60m of highest tier
                $extraMinutes = $duration - $maxTierDuration;
                $ratePerMinute = $tiers[$maxTierDuration] / $maxTierDuration;
                return round($tiers[$maxTierDuration] + ($extraMinutes * $ratePerMinute), 2);
            }
        }

        if ($duration === (int) $this->duration) {
            return (float) $this->price;
        }

        $baseDuration = (int) $this->duration;
        $basePrice = (float) $this->price;

        if ($baseDuration <= 0) {
            return $basePrice;
        }

        $hourlyRate = round(($basePrice / $baseDuration) * 60);

        if ($duration % 60 === 0) {
            $hours = $duration / 60;
            return (float) ($hours * $hourlyRate);
        }

        $halfHourRate = round($hourlyRate / 2);
        $hours = intdiv($duration, 60);
        $hasHalfHour = ($duration % 60) === 30;

        return (float) (($hours * $hourlyRate) + ($hasHalfHour ? $halfHourRate : 0));
    }
}
