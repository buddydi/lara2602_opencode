<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promotion extends Model
{
    use Loggable;
    
    protected static $logModule = 'promotion';
    protected static $logModuleName = '促销活动';
    
    protected $fillable = [
        'name',
        'type',
        'description',
        'discount_rate',
        'discount_amount',
        'min_amount',
        'reduce_amount',
        'start_time',
        'end_time',
        'max_quantity',
        'sold_quantity',
        'max_per_user',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'discount_rate' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'reduce_amount' => 'decimal:2',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_products')
            ->withPivot('special_price', 'stock_limit', 'sold_count')
            ->withTimestamps();
    }

    public static function getTypeOptions(): array
    {
        return [
            'flash_sale' => '秒杀',
            'discount' => '折扣',
            'full_reduce' => '满减',
        ];
    }

    public function isActive(): bool
    {
        return $this->is_active 
            && now()->gte($this->start_time) 
            && now()->lte($this->end_time);
    }

    public function calculateDiscount(float $price): float
    {
        if (!$this->isActive()) {
            return 0;
        }

        switch ($this->type) {
            case 'discount':
                return $price * (1 - $this->discount_rate / 100);
            case 'full_reduce':
                if ($price >= $this->min_amount) {
                    return $this->reduce_amount;
                }
                return 0;
            default:
                return 0;
        }
    }
}
