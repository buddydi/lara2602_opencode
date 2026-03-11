<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_amount',
        'max_discount',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
            'value' => 'decimal:2',
            'min_amount' => 'decimal:2',
            'max_discount' => 'decimal:2',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public static function getTypeOptions(): array
    {
        return [
            'fixed' => '固定金额',
            'percentage' => '百分比折扣',
        ];
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit > 0 && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canUse(int $customerId): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $userUsage = $this->usages()
            ->where('customer_id', $customerId)
            ->sum('used_count');

        return $userUsage < $this->per_user_limit;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if ($orderAmount < $this->min_amount) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return min($this->value, $orderAmount);
        }

        $discount = $orderAmount * ($this->value / 100);

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return $discount;
    }

    public function useCoupon(int $customerId, int $orderId): bool
    {
        if (!$this->canUse($customerId)) {
            return false;
        }

        $this->usages()->create([
            'customer_id' => $customerId,
            'order_id' => $orderId,
            'used_at' => now(),
        ]);

        $this->increment('usage_count');

        return true;
    }
}
