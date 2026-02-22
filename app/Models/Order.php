<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'order_no',
        'total_amount',
        'pay_amount',
        'freight',
        'product_count',
        'status',
        'pay_method',
        'paid_at',
        'shipping_no',
        'shipped_at',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNo(): string
    {
        return date('YmdHis') . rand(1000, 9999);
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => '待支付',
            'paid' => '已支付',
            'shipping' => '发货中',
            'shipped' => '已发货',
            'completed' => '已完成',
            'cancelled' => '已取消',
            'refunded' => '已退款',
            default => '未知',
        };
    }
}
