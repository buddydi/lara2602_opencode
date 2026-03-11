<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'customer_id',
        'type',
        'title',
        'content',
        'status',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public static function getTypeOptions(): array
    {
        return [
            'order' => '订单通知',
            'refund' => '退款通知',
            'system' => '系统通知',
        ];
    }

    public static function send(int $customerId, string $type, string $title, string $content, array $data = []): self
    {
        return static::create([
            'customer_id' => $customerId,
            'type' => $type,
            'title' => $title,
            'content' => $content,
            'data' => $data,
        ]);
    }

    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }

    public function getIsUnreadAttribute(): bool
    {
        return $this->status === 'unread';
    }
}
