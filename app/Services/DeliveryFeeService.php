<?php

namespace App\Services;

class DeliveryFeeService
{
    public function calculateDeliveryFee($state): int
    {
        $normalizedState = strtolower(trim((string) $state));

        if ($normalizedState === 'lagos') {
            return 1500;
        }

        return 3500;
    }
}

