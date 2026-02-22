<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('order');
    }

    public function allChildren(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->with('allChildren')->orderBy('order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public static function tree()
    {
        return static::with('allChildren')
            ->where('parent_id', null)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public static function flatTree()
    {
        return static::with('allChildren')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
