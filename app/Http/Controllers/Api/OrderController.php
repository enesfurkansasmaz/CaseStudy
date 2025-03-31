<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Discount;
use App\Models\DiscountRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Http\Requests\StoreOrderRequest;




class OrderController extends Controller
{
    public function listOrder()
    {
        return Order::with(['items.product', 'discounts'])->get();
    }

    public function addOrder(StoreOrderRequest $request)
    {
        try {
            $validated = $request->validated();

            return DB::transaction(function () use ($validated) {
                $orderItems = [];
                $orderTotal = 0;

                foreach ($validated['items'] as $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        throw ValidationException::withMessages([
                            'stock' => "Ürün '{$product->name}' için yeterli stok yok. Mevcut stok: {$product->stock}",
                        ]);
                    }

                    $lineTotal = $product->price * $item['quantity'];
                    $orderItems[] = new OrderItem([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'total' => $lineTotal,
                    ]);

                    $product->decrement('stock', $item['quantity']);

                    $orderTotal += $lineTotal;
                }

                $order = Order::create([
                    'customer_id' => $validated['customer_id'],
                    'total' => $orderTotal,
                ]);

                $order->items()->saveMany($orderItems);

                $discounts = $this->calculateDiscounts($order);
                $totalDiscount = array_sum(array_column($discounts, 'amount'));

                foreach ($discounts as $discount) {
                    Discount::create([
                        'order_id' => $order->id,
                        'reason' => $discount['reason'],
                        'amount' => $discount['amount'],
                    ]);
                }

                $order->update([
                    'total' => $orderTotal - $totalDiscount,
                ]);

                return response()->json($this->formatDiscountResponse($order), 201);
            });
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 400);
        }
    }

    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order bulunamadı'], 404);
        }

        return DB::transaction(function () use ($order) {
            try {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }

                $order->delete();

                return response()->json(['message' => 'Order başarıyla silindi.'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Order silinemedi', 'error' => $e->getMessage()], 400);
            }
        });
    }


    private function calculateDiscounts(Order $order): array
    {
        $rules = DiscountRule::with('parameters')->get();
        $discounts = [];

        foreach ($rules as $rule) {
            $className = 'App\\Services\\Discounts\\Calculators\\' . ucfirst(Str::camel($rule->type)) . 'Discount';

            if (class_exists($className)) {
                $calculator = new $className();
                $result = $calculator->calculate($order, $rule);

                if ($result) {
                    $discounts[] = $result;
                }
            }
        }

        return $discounts;
    }

    private function formatDiscountResponse(Order $order): array
    {
        $initialTotal = $order->items->sum(fn($i) => $i->unit_price * $i->quantity);
        $subtotal = $initialTotal;

        $discountsFormatted = [];

        foreach ($order->discounts as $discount) {
            $subtotal -= $discount->amount;
            $discountsFormatted[] = [
                'discountReason' => $discount->reason,
                'discountAmount' => number_format($discount->amount, 2, '.', ''),
                'subtotal' => number_format($subtotal, 2, '.', ''),
            ];
        }

        $totalDiscount = $order->discounts->sum('amount');
        $discountedTotal = $initialTotal - $totalDiscount;

        return [
            'orderId' => $order->id,
            'discounts' => $discountsFormatted,
            'totalDiscount' => number_format($totalDiscount, 2, '.', ''),
            'discountedTotal' => number_format($discountedTotal, 2, '.', ''),
        ];
    }
}
