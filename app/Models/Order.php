<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Discount;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'total',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

}
