<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointsRecord extends Model
{
    use Loggable;
    
    protected static $logModule = 'points';
    protected static $logModuleName = '积分';
    
    protected $fillable = [
        'customer_id',
        'order_id',
        'points',
        'type',
        'description',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function getTypeOptions(): array
    {
        return [
            'order_complete' => '订单完成奖励',
            'order_use' => '订单抵扣',
            'admin_add' => '管理员添加',
            'admin_deduct' => '管理员扣减',
        ];
    }
}
