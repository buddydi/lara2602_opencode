<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'is_required',
        'is_filterable',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
        ];
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id')->orderBy('order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_id');
    }
}
