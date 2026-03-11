<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingCompany extends Model
{
    protected $fillable = [
        'code',
        'name',
        'website',
        'is_active',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'shipping_company', 'code');
    }

    public static function getActiveCompanies()
    {
        return static::where('is_active', true)->orderBy('sort')->get();
    }

    public static function getCodeName($code)
    {
        $company = static::where('code', $code)->first();
        return $company ? $company->name : $code;
    }
}
