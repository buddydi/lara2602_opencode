<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;

class FrontHomeController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $products = Product::where('status', 'published')->latest()->take(8)->get();
        
        return view('front.home', compact('categories', 'products'));
    }

    public function products(Request $request)
    {
        $products = Product::where('status', 'published')
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->brand_id, fn($q) => $q->where('brand_id', $request->brand_id))
            ->when($request->keyword, fn($q) => $q->where('name', 'like', "%{$request->keyword}%"))
            ->latest()
            ->paginate(20);
        
        $categories = ProductCategory::where('is_active', true)->with('children')->get();
        $brands = Brand::where('is_active', true)->get();
        
        return view('front.products', compact('products', 'categories', 'brands'));
    }

    public function productDetail(Product $product)
    {
        $product->load(['category', 'brand', 'skus', 'attributeValues.attribute', 'attributeValues.attributeValue']);
        
        $relatedProducts = Product::where('status', 'published')
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();
        
        return view('front.product-detail', compact('product', 'relatedProducts'));
    }
}
