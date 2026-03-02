<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository
{
    public function getAllProducts(array $filters = [], int $perPage = 15)
    {
        $query = Product::with(['categories', 'skus', 'images']);

        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('product_categories.id', $filters['category_id']);
            });
        }

        if (!empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['keyword']}%")
                  ->orWhere('sku', 'like', "%{$filters['keyword']}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['is_featured'])) {
            $query->where('is_featured', true);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getProductById(int $id): ?Product
    {
        return Product::with(['categories', 'skus', 'skus.attributes', 'images', 'attributes'])
            ->find($id);
    }

    public function getProductBySku(string $sku): ?Product
    {
        return Product::with(['skus', 'images'])
            ->where('sku', $sku)
            ->first();
    }

    public function getProductsByCategory(int $categoryId, int $perPage = 15)
    {
        return Product::with(['categories', 'skus', 'images'])
            ->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('product_categories.id', $categoryId);
            })
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function searchProducts(string $keyword, int $perPage = 15)
    {
        return Product::with(['categories', 'skus', 'images'])
            ->where('is_active', true)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getFeaturedProducts(int $limit = 10)
    {
        return Product::with(['images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->limit($limit)
            ->get();
    }

    public function getActiveProducts(int $perPage = 15)
    {
        return Product::with(['categories', 'skus', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
