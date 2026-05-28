<?php

namespace App\Services;

use App\Models\Quotation;
use App\Models\User;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

class QuotationService
{
    private const FIXED_RATE_PER_DAY = 3;

    public function createQuotation(
        User $user,
        array $ages,
        string $currencyId,
        string $startDate,
        string $endDate
    ): Quotation {
        $tripLength = $this->calculateTripLength($startDate, $endDate);
        $total = $this->calculateTotal($ages, $tripLength);

        return Quotation::create([
            'user_id' => $user->id,
            'ages' => $ages,
            'currency_id' => $currencyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'trip_length' => $tripLength,
            'total' => $total,
        ]);
    }

    public function calculateTripLength(string $startDate, string $endDate): int
    {
        $start = CarbonImmutable::createFromFormat('Y-m-d', $startDate);
        $end = CarbonImmutable::createFromFormat('Y-m-d', $endDate);

        return $start->diffInDays($end) + 1;
    }

    public function calculateTotal(array $ages, int $tripLength): float
    {
        $total = 0;

        foreach ($ages as $age) {
            $total += self::FIXED_RATE_PER_DAY * $this->resolveAgeLoad((int) $age) * $tripLength;
        }

        return round($total, 2);
    }

    public function resolveAgeLoad(int $age): float
    {
        return match (true) {
            $age >= 18 && $age <= 30 => 0.6,
            $age >= 31 && $age <= 40 => 0.7,
            $age >= 41 && $age <= 50 => 0.8,
            $age >= 51 && $age <= 60 => 0.9,
            $age >= 61 && $age <= 70 => 1.0,
            default => throw new InvalidArgumentException('Age must be between 18 and 70.'),
        };
    }
}
