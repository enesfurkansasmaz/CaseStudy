<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'reason', 'amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

