<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    use Loggable;
    
    protected static $logModule = 'stock';
    protected static $logModuleName = '库存记录';
    
    protected $fillable = [
        'product_id',
        'product_sku_id',
        'user_id',
        'type',
        'quantity',
        'balance',
        'order_no',
        'reason',
        'remark',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'balance' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(array $data): self
    {
        $log = static::create($data);

        $sku = $log->sku;
        if ($sku) {
            $sku->stock = $log->balance;
            $sku->save();

            $alert = StockAlert::where('product_id', $log->product_id)
                ->where('product_sku_id', $log->product_sku_id)
                ->first();
            
            if ($alert) {
                $alert->checkAndNotify($log->balance);
            }
        }

        return $log;
    }

    public static function getTypeOptions(): array
    {
        return [
            'in' => '入库',
            'out' => '出库',
            'adjust' => '调整',
        ];
    }

    public static function getReasonOptions(): array
    {
        return [
            'purchase' => '采购入库',
            'return' => '退货入库',
            'order' => '订单出库',
            'damage' => '损耗',
            'adjustment' => '库存调整',
            'gift' => '赠品',
            'other' => '其他',
        ];
    }
}
