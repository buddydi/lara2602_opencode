<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'keyword' => $request->input('keyword'),
            'category_id' => $request->input('category_id'),
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured'),
        ];

        $perPage = $request->input('per_page', 15);
        $products = $this->productRepository->getAllProducts($filters, $perPage);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->getProductById($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => '产品不存在'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function byCategory(Request $request, int $categoryId): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $products = $this->productRepository->getProductsByCategory($categoryId, $perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $keyword = $request->input('q', '');
        
        if (strlen($keyword) < 2) {
            return response()->json([
                'success' => false,
                'message' => '搜索关键词至少2个字符'
            ], 400);
        }

        $perPage = $request->input('per_page', 15);
        $products = $this->productRepository->searchProducts($keyword, $perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function featured(): JsonResponse
    {
        $products = $this->productRepository->getFeaturedProducts(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
