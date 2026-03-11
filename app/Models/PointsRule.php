<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PointsRule extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $cacheKey = "points_rule_{$key}";
        
        return Cache::rememberForever($cacheKey, function () use ($key, $default) {
            $rule = static::where('key', $key)->first();
            return $rule ? $rule->value : $default;
        });
    }

    public static function setValue(string $key, string $value): bool
    {
        $rule = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("points_rule_{$key}");
        
        return true;
    }

    public static function getAllRules(): array
    {
        return Cache::rememberForever('points_rules_all', function () {
            return static::all()->pluck('value', 'key')->toArray();
        });
    }

    public static function getPointsRate(): int
    {
        return (int) static::getValue('points_rate', 1);
    }

    public static function getDeductionRate(): int
    {
        return (int) static::getValue('deduction_rate', 100);
    }

    public static function getMaxDeductionPercent(): int
    {
        return (int) static::getValue('max_deduction', 50);
    }

    public static function getMaxDeductionPoints(int $orderAmount): int
    {
        $percent = self::getMaxDeductionPercent();
        return intval($orderAmount * $percent / 100 * self::getDeductionRate());
    }

    public static function getMemberLevelConfig(): array
    {
        return [
            'bronze' => [
                'name' => '青铜',
                'min_points' => (int) static::getValue('bronze_min', 0),
                'discount' => (float) static::getValue('bronze_discount', 1.0),
            ],
            'silver' => [
                'name' => '白银',
                'min_points' => (int) static::getValue('silver_min', 1000),
                'discount' => (float) static::getValue('silver_discount', 0.98),
            ],
            'gold' => [
                'name' => '黄金',
                'min_points' => (int) static::getValue('gold_min', 5000),
                'discount' => (float) static::getValue('gold_discount', 0.95),
            ],
            'platinum' => [
                'name' => '铂金',
                'min_points' => (int) static::getValue('platinum_min', 20000),
                'discount' => (float) static::getValue('platinum_discount', 0.92),
            ],
            'diamond' => [
                'name' => '钻石',
                'min_points' => (int) static::getValue('diamond_min', 50000),
                'discount' => (float) static::getValue('diamond_discount', 0.88),
            ],
        ];
    }

    public static function clearCache(): void
    {
        $rules = static::all()->pluck('key')->toArray();
        foreach ($rules as $key) {
            Cache::forget("points_rule_{$key}");
        }
        Cache::forget('points_rules_all');
    }
}
