<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAlert extends Model
{
    use Loggable;
    
    protected static $logModule = 'stock';
    protected static $logModuleName = '库存预警';
    
    protected $fillable = [
        'product_id',
        'product_sku_id',
        'low_stock_threshold',
        'critical_stock_threshold',
        'is_enabled',
        'notify_admin',
        'notify_customer',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'notify_admin' => 'boolean',
        'notify_customer' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }

    public function checkAndNotify(int $currentStock): bool
    {
        if (!$this->is_enabled) {
            return false;
        }

        $notified = false;

        if ($currentStock <= $this->critical_stock_threshold) {
            $this->sendNotification('critical');
            $notified = true;
        } elseif ($currentStock <= $this->low_stock_threshold) {
            $this->sendNotification('low');
            $notified = true;
        }

        return $notified;
    }

    protected function sendNotification(string $level): void
    {
        $title = $level === 'critical' ? '紧急库存预警' : '低库存预警';
        $skuStock = $this->sku ? $this->sku->stock : 0;
        $content = "商品「{$this->product->name}」库存不足，当前库存：{$skuStock}";

        if ($this->notify_admin) {
            Notification::send(
                1,
                'system',
                $title,
                $content
            );
        }
    }
}
