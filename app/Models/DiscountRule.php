<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public function parameters()
    {
        return $this->hasMany(DiscountRuleParameter::class);
    }

    public function getParameter($key, $default = null)
    {
        $param = $this->parameters->firstWhere('param_key', $key);
        return $param ? $param->param_value : $default;
    }
}
