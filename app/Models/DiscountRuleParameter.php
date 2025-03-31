<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRuleParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_rule_id',
        'param_key',
        'param_value',
    ];

    public function rule()
    {
        return $this->belongsTo(DiscountRule::class, 'discount_rule_id');
    }
}
