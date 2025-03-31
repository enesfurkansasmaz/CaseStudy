<?php

namespace App\Services\Discounts\Calculators;

use App\Models\Order;
use App\Models\DiscountRule;
use App\Services\Discounts\Contracts\DiscountCalculatorInterface;

class CheapestPercentageDiscount implements DiscountCalculatorInterface
{
    public function calculate(Order $order, DiscountRule $rule): ?array
    {
        $categoryId = (int)$rule->getParameter('category_id');
        $buyQuantity = (int)$rule->getParameter('buy_quantity', 0);
        $percentage = (float)$rule->getParameter('percentage', 0);

        $items = $order->items()->with('product')->get();
        $categoryItems = $items->filter(fn($item) => $item->product->category == $categoryId);

        if ($categoryItems->sum('quantity') >= $buyQuantity) {
            $cheapestItem = $categoryItems->sortBy('unit_price')->first();
            if ($cheapestItem) {
                $amount = round($cheapestItem->unit_price * ($percentage / 100), 2);
                return ['reason' => $rule->name, 'amount' => $amount];
            }
        }

        return null;
    }
}
