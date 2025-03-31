<?php

namespace App\Services\Discounts\Calculators;

use App\Models\Order;
use App\Models\DiscountRule;
use App\Services\Discounts\Contracts\DiscountCalculatorInterface;

class BuyXGetYFreeDiscount implements DiscountCalculatorInterface
{
    public function calculate(Order $order, DiscountRule $rule): ?array
    {
        $categoryId = (int)$rule->getParameter('category_id');
        $buyQuantity = (int)$rule->getParameter('buy_quantity', 0);
        $freeQuantity = (int)$rule->getParameter('free_quantity', 0);

        $items = $order->items()->with('product')->get();

        foreach ($items as $item) {
            if ($item->product->category == $categoryId && $item->quantity >= $buyQuantity) {
                $freeItems = floor($item->quantity / $buyQuantity) * $freeQuantity;
                $amount = round($freeItems * $item->unit_price, 2);

                if ($amount > 0) {
                    return ['reason' => $rule->name, 'amount' => $amount];
                }
            }
        }

        return null;
    }
}
