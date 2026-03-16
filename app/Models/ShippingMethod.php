<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'first_weight',
        'first_price',
        'continue_weight',
        'continue_price',
        'free_shipping_amount',
        'estimated_days',
        'order',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'order' => 'integer',
        'first_weight' => 'decimal:2',
        'first_price' => 'decimal:2',
        'continue_weight' => 'decimal:2',
        'continue_price' => 'decimal:2',
        'free_shipping_amount' => 'decimal:2',
        'estimated_days' => 'integer',
    ];
}
