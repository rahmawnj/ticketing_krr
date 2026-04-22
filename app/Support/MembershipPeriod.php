<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class MembershipPeriod
{
    public static function expiryFromStart(CarbonInterface $startDate, int $durationDays): Carbon
    {
        $startDate = Carbon::parse($startDate->format('Y-m-d'))->startOfDay();

        if ($durationDays <= 0) {
            return $startDate;
        }

        $monthCycleCount = self::monthCycleCount($durationDays);
        if ($monthCycleCount !== null) {
            return $startDate->copy()->addMonthsNoOverflow($monthCycleCount);
        }

        return $startDate->copy()->addDays($durationDays);
    }

    public static function standardRenewalPeriod(
        CarbonInterface $anchorStartDate,
        CarbonInterface $referenceExpiredAt,
        int $durationDays
    ): array {
        $anchorStartDate = Carbon::parse($anchorStartDate->format('Y-m-d'))->startOfDay();
        $referenceExpiredAt = Carbon::parse($referenceExpiredAt->format('Y-m-d'))->startOfDay();

        $cycleNumber = 1;
        $nextExpiredAt = self::expiryAfterCycles($anchorStartDate, $durationDays, $cycleNumber);

        while ($nextExpiredAt->lessThanOrEqualTo($referenceExpiredAt)) {
            $cycleNumber++;
            $nextExpiredAt = self::expiryAfterCycles($anchorStartDate, $durationDays, $cycleNumber);
        }

        $previousExpiredAt = $cycleNumber > 1
            ? self::expiryAfterCycles($anchorStartDate, $durationDays, $cycleNumber - 1)
            : $anchorStartDate->copy();

        return [
            'start_date' => $previousExpiredAt->copy()->addDay(),
            'expired_at' => $nextExpiredAt,
        ];
    }

    public static function renewalStartDate(CarbonInterface $previousExpiredAt): Carbon
    {
        return Carbon::parse($previousExpiredAt->format('Y-m-d'))->startOfDay()->addDay();
    }

    public static function renewalExpiryDate(CarbonInterface $previousExpiredAt, int $durationDays): Carbon
    {
        return self::expiryFromStart($previousExpiredAt, $durationDays);
    }

    private static function expiryAfterCycles(CarbonInterface $anchorStartDate, int $durationDays, int $cycleNumber): Carbon
    {
        $anchorStartDate = Carbon::parse($anchorStartDate->format('Y-m-d'))->startOfDay();
        $cycleNumber = max($cycleNumber, 1);

        if ($durationDays <= 0) {
            return $anchorStartDate;
        }

        $monthCycleCount = self::monthCycleCount($durationDays);
        if ($monthCycleCount !== null) {
            return $anchorStartDate->copy()->addMonthsNoOverflow($monthCycleCount * $cycleNumber);
        }

        return $anchorStartDate->copy()->addDays($durationDays * $cycleNumber);
    }

    private static function monthCycleCount(int $durationDays): ?int
    {
        if ($durationDays <= 0) {
            return null;
        }

        foreach ([30, 31] as $monthlyDuration) {
            if ($durationDays % $monthlyDuration === 0) {
                return (int) ($durationDays / $monthlyDuration);
            }
        }

        return null;
    }
}
