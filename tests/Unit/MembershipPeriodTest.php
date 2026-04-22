<?php

namespace Tests\Unit;

use App\Support\MembershipPeriod;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class MembershipPeriodTest extends TestCase
{
    public function test_monthly_membership_keeps_the_same_calendar_day_on_registration()
    {
        $startDate = Carbon::create(2026, 3, 18, 10, 0, 0, 'Asia/Jakarta');

        $expiredAt = MembershipPeriod::expiryFromStart($startDate, 30);

        $this->assertSame('2026-04-18', $expiredAt->format('Y-m-d'));
    }

    public function test_31_day_monthly_membership_keeps_the_same_calendar_day_on_registration()
    {
        $startDate = Carbon::create(2026, 3, 13, 10, 0, 0, 'Asia/Jakarta');

        $expiredAt = MembershipPeriod::expiryFromStart($startDate, 31);

        $this->assertSame('2026-04-13', $expiredAt->format('Y-m-d'));
    }

    public function test_yearly_membership_keeps_the_same_calendar_day()
    {
        $startDate = Carbon::create(2026, 3, 18, 10, 0, 0, 'Asia/Jakarta');

        $expiredAt = MembershipPeriod::expiryFromStart($startDate, 360);

        $this->assertSame('2027-03-18', $expiredAt->format('Y-m-d'));
    }

    public function test_late_renewal_keeps_the_existing_membership_cycle()
    {
        $previousExpiredAt = Carbon::create(2026, 4, 18, 0, 0, 0, 'Asia/Jakarta');

        $renewalStart = MembershipPeriod::renewalStartDate($previousExpiredAt);
        $renewalExpiredAt = MembershipPeriod::renewalExpiryDate($previousExpiredAt, 30);

        $this->assertSame('2026-04-19', $renewalStart->format('Y-m-d'));
        $this->assertSame('2026-05-18', $renewalExpiredAt->format('Y-m-d'));
    }

    public function test_standard_renewal_can_return_to_the_original_join_cycle_after_old_date_drift()
    {
        $anchorStartDate = Carbon::create(2026, 3, 18, 10, 0, 0, 'Asia/Jakarta');
        $currentExpiredAt = Carbon::create(2026, 5, 21, 0, 0, 0, 'Asia/Jakarta');

        $renewalPeriod = MembershipPeriod::standardRenewalPeriod($anchorStartDate, $currentExpiredAt, 30);

        $this->assertSame('2026-05-19', $renewalPeriod['start_date']->format('Y-m-d'));
        $this->assertSame('2026-06-18', $renewalPeriod['expired_at']->format('Y-m-d'));
    }

    public function test_standard_renewal_keeps_the_original_join_day_for_regular_renewal()
    {
        $anchorStartDate = Carbon::create(2026, 3, 18, 10, 0, 0, 'Asia/Jakarta');
        $currentExpiredAt = Carbon::create(2026, 4, 18, 0, 0, 0, 'Asia/Jakarta');

        $renewalPeriod = MembershipPeriod::standardRenewalPeriod($anchorStartDate, $currentExpiredAt, 30);

        $this->assertSame('2026-04-19', $renewalPeriod['start_date']->format('Y-m-d'));
        $this->assertSame('2026-05-18', $renewalPeriod['expired_at']->format('Y-m-d'));
    }

    public function test_31_day_standard_renewal_keeps_the_original_join_day()
    {
        $anchorStartDate = Carbon::create(2026, 3, 13, 10, 0, 0, 'Asia/Jakarta');
        $currentExpiredAt = Carbon::create(2026, 4, 13, 0, 0, 0, 'Asia/Jakarta');

        $renewalPeriod = MembershipPeriod::standardRenewalPeriod($anchorStartDate, $currentExpiredAt, 31);

        $this->assertSame('2026-04-14', $renewalPeriod['start_date']->format('Y-m-d'));
        $this->assertSame('2026-05-13', $renewalPeriod['expired_at']->format('Y-m-d'));
    }

    public function test_non_monthly_duration_still_uses_day_based_calculation()
    {
        $startDate = Carbon::create(2026, 3, 18, 10, 0, 0, 'Asia/Jakarta');

        $expiredAt = MembershipPeriod::expiryFromStart($startDate, 14);

        $this->assertSame('2026-04-01', $expiredAt->format('Y-m-d'));
    }
}
