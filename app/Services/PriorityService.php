<?php

namespace App\Services;

use App\Models\Refugee;
use Carbon\Carbon;

class PriorityService
{
    // Higher weight = higher priority category
    const CATEGORY_WEIGHTS = [
        'healthcare'  => 1.5,
        'protection'  => 1.4,
        'food'        => 1.3,
        'shelter'     => 1.2,
        'education'   => 1.0,
    ];

    /**
     * Calculate a weighted vulnerability priority score.
     *
     * Formula: (urgency_level × 20 + vulnerability_bonus) × category_weight
     *
     * Max possible: (5×20 + 95) × 1.5 = 292.50
     */
    public function calculate(array $data, Refugee $refugee): float
    {
        $baseScore = $data['urgency_level'] * 20;

        $vulnerabilityBonus = 0;
        $age = Carbon::parse($refugee->date_of_birth)->age;

        if ($age < 5 || $age > 65)     $vulnerabilityBonus += 20; // children / elderly
        if ($data['has_critical_health']) $vulnerabilityBonus += 30;
        if ($data['has_disability'])    $vulnerabilityBonus += 15;
        if ($data['is_pregnant'])       $vulnerabilityBonus += 20;
        if ($data['family_size'] > 5)  $vulnerabilityBonus += 10;

        $weight = self::CATEGORY_WEIGHTS[$data['category']] ?? 1.0;

        return round(($baseScore + $vulnerabilityBonus) * $weight, 2);
    }
}
