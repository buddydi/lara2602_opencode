<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'points',
        'member_level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function pointsRecords(): HasMany
    {
        return $this->hasMany(PointsRecord::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('status', 'unread');
    }

    public function afterSales(): HasMany
    {
        return $this->hasMany(AfterSale::class);
    }

    public static function getMemberLevelConfig(): array
    {
        return PointsRule::getMemberLevelConfig();
    }

    public function getMemberLevelNameAttribute(): string
    {
        return self::getMemberLevelConfig()[$this->member_level]['name'] ?? '青铜';
    }

    public function getMemberDiscountAttribute(): float
    {
        return self::getMemberLevelConfig()[$this->member_level]['discount'] ?? 1.0;
    }

    public function getNextLevelAttribute(): ?array
    {
        $config = self::getMemberLevelConfig();
        $levels = array_keys($config);
        $currentIndex = array_search($this->member_level, $levels);
        
        if ($currentIndex === false || $currentIndex >= count($levels) - 1) {
            return null;
        }
        
        $nextLevel = $levels[$currentIndex + 1];
        return [
            'level' => $nextLevel,
            'name' => $config[$nextLevel]['name'],
            'min_points' => $config[$nextLevel]['min_points'],
            'points_needed' => $config[$nextLevel]['min_points'] - $this->points,
        ];
    }

    public function addPoints(int $points, string $type, ?int $orderId = null, ?string $description = null): void
    {
        $this->increment('points', $points);
        
        PointsRecord::create([
            'customer_id' => $this->id,
            'order_id' => $orderId,
            'points' => $points,
            'type' => $type,
            'description' => $description ?? "获得 {$points} 积分",
        ]);

        $this->updateMemberLevel();
    }

    public function usePoints(int $points, string $type, ?int $orderId = null, ?string $description = null): bool
    {
        if ($this->points < $points) {
            return false;
        }

        $this->decrement('points', $points);

        PointsRecord::create([
            'customer_id' => $this->id,
            'order_id' => $orderId,
            'points' => -$points,
            'type' => $type,
            'description' => $description ?? "使用 {$points} 积分",
        ]);

        return true;
    }

    public function updateMemberLevel(): void
    {
        $config = self::getMemberLevelConfig();
        $currentLevel = $this->member_level;
        
        foreach (array_reverse($config) as $level => $settings) {
            if ($this->points >= $settings['min_points']) {
                if ($level !== $currentLevel) {
                    $this->update(['member_level' => $level]);
                }
                break;
            }
        }
    }
}
