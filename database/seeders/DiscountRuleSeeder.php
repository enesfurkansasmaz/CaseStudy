<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscountRule;

class DiscountRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'name' => '10_PERCENT_OVER_1000',
                'type' => 'PERCENTAGE',
                'parameters' => [
                    ['param_key' => 'min_order_total', 'param_value' => '1000'],
                    ['param_key' => 'percentage', 'param_value' => '10'],
                ],
            ],
            [
                'name' => 'BUY_5_GET_1_CATEGORY_2',
                'type' => 'BUY_X_GET_Y_FREE',
                'parameters' => [
                    ['param_key' => 'category_id', 'param_value' => '2'],
                    ['param_key' => 'buy_quantity', 'param_value' => '6'],
                    ['param_key' => 'free_quantity', 'param_value' => '1'],
                ],
            ],
            [
                'name' => '20_PERCENT_CHEAPEST_CAT_1',
                'type' => 'CHEAPEST_PERCENTAGE',
                'parameters' => [
                    ['param_key' => 'category_id', 'param_value' => '1'],
                    ['param_key' => 'buy_quantity', 'param_value' => '2'],
                    ['param_key' => 'percentage', 'param_value' => '20'],
                ],
            ],
        ];

        foreach ($rules as $ruleData) {
            $rule = DiscountRule::create([
                'name' => $ruleData['name'],
                'type' => $ruleData['type'],
            ]);

            $rule->parameters()->createMany($ruleData['parameters']);
        }
    }
}
