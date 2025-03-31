<?php

namespace App\Services\Discounts\Calculators;

use App\Models\Order;
use App\Models\DiscountRule;
use App\Services\Discounts\Contracts\DiscountCalculatorInterface;

class PercentageDiscount implements DiscountCalculatorInterface
{
    public function calculate(Order $order, DiscountRule $rule): ?array
    {
        $minOrderTotal = (float)$rule->getParameter('min_order_total', 0);
        $percentage = (float)$rule->getParameter('percentage', 0);

        if ($order->total >= $minOrderTotal) {
            $amount = round($order->total * ($percentage / 100), 2);
            return ['reason' => $rule->name, 'amount' => $amount];
        }

        return null;
    }
}
