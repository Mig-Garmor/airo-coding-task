<?php

namespace Tests\Unit;

use App\Services\QuotationService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class QuotationServiceTest extends TestCase
{
    private QuotationService $quotationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->quotationService = new QuotationService;
    }

    public function test_it_calculates_inclusive_trip_length(): void
    {
        $tripLength = $this->quotationService->calculateTripLength(
            '2020-10-01',
            '2020-10-30'
        );

        $this->assertSame(30, $tripLength);
    }

    public function test_it_calculates_one_day_trip_length_when_dates_are_same(): void
    {
        $tripLength = $this->quotationService->calculateTripLength(
            '2020-10-01',
            '2020-10-01'
        );

        $this->assertSame(1, $tripLength);
    }

    public function test_it_calculates_total_for_worked_example(): void
    {
        $total = $this->quotationService->calculateTotal([28, 35], 30);

        $this->assertSame(117.0, $total);
    }

    public function test_it_resolves_age_load_boundaries(): void
    {
        $this->assertSame(0.6, $this->quotationService->resolveAgeLoad(18));
        $this->assertSame(0.6, $this->quotationService->resolveAgeLoad(30));

        $this->assertSame(0.7, $this->quotationService->resolveAgeLoad(31));
        $this->assertSame(0.7, $this->quotationService->resolveAgeLoad(40));

        $this->assertSame(0.8, $this->quotationService->resolveAgeLoad(41));
        $this->assertSame(0.8, $this->quotationService->resolveAgeLoad(50));

        $this->assertSame(0.9, $this->quotationService->resolveAgeLoad(51));
        $this->assertSame(0.9, $this->quotationService->resolveAgeLoad(60));

        $this->assertSame(1.0, $this->quotationService->resolveAgeLoad(61));
        $this->assertSame(1.0, $this->quotationService->resolveAgeLoad(70));
    }

    public function test_it_rejects_age_below_supported_range(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->quotationService->resolveAgeLoad(17);
    }

    public function test_it_rejects_age_above_supported_range(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->quotationService->resolveAgeLoad(71);
    }
}
