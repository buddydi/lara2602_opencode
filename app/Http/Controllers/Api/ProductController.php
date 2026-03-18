<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->keyword) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->keyword}%")
                  ->orWhere('description', 'like', "%{$request->keyword}%");
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('id', 'desc')->paginate($request->per_page ?? 15);

        return $this->success($products);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'attributes', 'skus']);
        return $this->success($product);
    }

    public function featured()
    {
        $products = Product::with(['category', 'brand'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        return $this->success($products);
    }

    public function search(Request $request)
    {
        $keyword = $request->q ?? $request->keyword ?? '';
        
        if (strlen($keyword) < 2) {
            return $this->error('搜索关键词至少2个字符', 400);
        }

        $products = Product::with(['category', 'brand'])
            ->where('name', 'like', "%{$keyword}%")
            ->orderBy('id', 'desc')
            ->paginate($request->per_page ?? 15);

        return $this->success($products);
    }

    public function byCategory(Request $request, int $categoryId)
    {
        $products = Product::with(['category', 'brand'])
            ->where('category_id', $categoryId)
            ->orderBy('id', 'desc')
            ->paginate($request->per_page ?? 15);

        return $this->success($products);
    }

    public function categories()
    {
        $categories = ProductCategory::where('is_active', true)->get();
        return $this->success($categories);
    }
}
