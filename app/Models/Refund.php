<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refund extends Model
{
    use Loggable;
    
    protected static $logModule = 'refund';
    protected static $logModuleName = '退款';
    
    protected $fillable = [
        'order_id',
        'customer_id',
        'amount',
        'reason',
        'description',
        'status',
        'reject_reason',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => '待审核',
            'approved' => '已退款',
            'rejected' => '已拒绝',
            default => '未知',
        };
    }
}
