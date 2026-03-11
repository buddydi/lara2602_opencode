<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use Loggable;
    
    protected static $logModule = 'order';
    protected static $logModuleName = '订单';
    
    protected $fillable = [
        'customer_id',
        'address_id',
        'order_no',
        'total_amount',
        'shipping_fee',
        'pay_amount',
        'freight',
        'product_count',
        'status',
        'pay_method',
        'payment_method',
        'paid_at',
        'shipping_no',
        'shipping_company',
        'shipping_traces',
        'shipped_at',
        'remark',
        'shipping_method',
        'points_used',
        'points_deduction',
        'coupon_id',
        'coupon_discount',
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

    public function reviews(): HasMany
    {
        return $this->hasMany(OrderReview::class)->where('status', 'approved');
    }

    public function allReviews(): HasMany
    {
        return $this->hasMany(OrderReview::class);
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function afterSales(): HasMany
    {
        return $this->hasMany(AfterSale::class);
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
