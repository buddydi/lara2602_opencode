<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait Loggable
{
    public static function bootLoggable()
    {
        static::created(function ($model) {
            self::logAction('create', $model);
        });

        static::updated(function ($model) {
            if ($model->wasChanged()) {
                self::logAction('update', $model, [
                    'old_values' => $model->getOriginal(),
                    'new_values' => $model->getChanges(),
                ]);
            }
        });

        static::deleted(function ($model) {
            self::logAction('delete', $model);
        });
    }

    protected static function logAction(string $action, $model, array $extra = [])
    {
        $module = static::getLogModule();
        
        ActivityLog::log(array_merge([
            'module' => $module,
            'action' => $action,
            'description' => static::getLogDescription($action, $model),
            'target_type' => get_class($model),
            'target_id' => $model->getKey(),
        ], $extra));
    }

    protected static function getLogModule(): string
    {
        return property_exists(static::class, 'logModule') 
            ? static::$logModule 
            : strtolower(class_basename(static::class));
    }

    protected static function getLogDescription(string $action, $model): string
    {
        $name = $model->name ?? $model->title ?? $model->order_no ?? '#' . $model->getKey();
        $actionText = [
            'create' => '创建',
            'update' => '更新',
            'delete' => '删除',
        ][$action] ?? $action;

        return "{$actionText}了" . static::getLogModuleName() . "：{$name}";
    }

    protected static function getLogModuleName(): string
    {
        return property_exists(static::class, 'logModuleName') 
            ? static::$logModuleName 
            : class_basename(static::class);
    }
}
