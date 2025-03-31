<?php

namespace App\Services\Discounts\Contracts;

use App\Models\Order;
use App\Models\DiscountRule;

interface DiscountCalculatorInterface
{
    public function calculate(Order $order, DiscountRule $rule): ?array;
}
