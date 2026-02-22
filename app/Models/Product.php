<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'price',
        'original_price',
        'cover_image',
        'category_id',
        'brand_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function skus(): HasMany
    {
        return $this->hasMany(ProductSku::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }
}
