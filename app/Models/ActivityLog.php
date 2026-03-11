<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'description',
        'target_type',
        'target_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo($this->target_type, 'target_id');
    }

    public static function log(array $data): self
    {
        return static::create([
            'user_id' => auth()->id() ?? null,
            'module' => $data['module'],
            'action' => $data['action'],
            'description' => $data['description'] ?? '',
            'target_type' => $data['target_type'] ?? null,
            'target_id' => $data['target_id'] ?? null,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function getModuleOptions(): array
    {
        return [
            'user' => '用户',
            'role' => '角色',
            'permission' => '权限',
            'customer' => '客户',
            'order' => '订单',
            'product' => '商品',
            'coupon' => '优惠券',
            'refund' => '退款',
            'invoice' => '发票',
            'after-sale' => '售后',
            'notification' => '通知',
            'system' => '系统',
        ];
    }

    public static function getActionOptions(): array
    {
        return [
            'create' => '创建',
            'update' => '更新',
            'delete' => '删除',
            'login' => '登录',
            'logout' => '登出',
            'view' => '查看',
            'export' => '导出',
            'import' => '导入',
            'approve' => '审批',
            'reject' => '拒绝',
        ];
    }
}
