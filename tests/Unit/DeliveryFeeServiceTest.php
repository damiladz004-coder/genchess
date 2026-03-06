<?php

namespace Tests\Unit;

use App\Services\DeliveryFeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryFeeServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_delivery_fee_for_lagos_state(): void
    {
        $service = new DeliveryFeeService();

        $this->assertSame(1500, $service->calculateDeliveryFee('Lagos'));
        $this->assertSame(1500, $service->calculateDeliveryFee('lagos'));
        $this->assertSame(1500, $service->calculateDeliveryFee('LAGOS'));
        $this->assertSame(1500, $service->calculateDeliveryFee('  lagos  '));
    }

    public function test_calculates_delivery_fee_for_non_lagos_states(): void
    {
        $service = new DeliveryFeeService();

        $this->assertSame(3500, $service->calculateDeliveryFee('Abuja'));
        $this->assertSame(3500, $service->calculateDeliveryFee('Rivers'));
    }
}

