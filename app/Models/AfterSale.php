<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AfterSale extends Model
{
    use Loggable;
    
    protected static $logModule = 'after-sale';
    protected static $logModuleName = '售后';
    
    protected $fillable = [
        'order_id',
        'customer_id',
        'order_item_id',
        'type',
        'status',
        'reason',
        'description',
        'admin_remark',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public static function getTypeOptions(): array
    {
        return [
            'return' => '退货',
            'exchange' => '换货',
        ];
    }

    public static function getStatusOptions(): array
    {
        return [
            'pending' => '待处理',
            'processing' => '处理中',
            'completed' => '已完成',
            'rejected' => '已拒绝',
        ];
    }
}
