<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'since',
        'revenue',
    ];

    protected $casts = [
        'since' => 'date',
        'revenue' => 'decimal:2',
    ];
}
