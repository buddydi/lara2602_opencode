<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use Loggable;
    
    protected static $logModule = 'invoice';
    protected static $logModuleName = '发票';
    
    protected $fillable = [
        'order_id',
        'customer_id',
        'type',
        'title',
        'tax_no',
        'email',
        'phone',
        'address',
        'amount',
        'status',
        'invoice_no',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
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
            'pending' => '待开',
            'issued' => '已开',
            default => '未知',
        };
    }

    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'personal' => '个人',
            'company' => '企业',
            default => '未知',
        };
    }
}
