<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class ProductSkuController extends Controller
{
    public function index(Product $product)
    {
        $skus = $product->skus()->orderBy('id')->get();
        return view('product-skus.index', compact('product', 'skus'));
    }

    public function create(Product $product)
    {
        return view('product-skus.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:product_skus,sku',
            'name' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['product_id'] = $product->id;
        
        ProductSku::create($validated);

        return redirect()->route('products.skus.index', $product)->with('success', 'SKU创建成功');
    }

    public function edit(Product $product, ProductSku $sku)
    {
        return view('product-skus.edit', compact('product', 'sku'));
    }

    public function update(Request $request, Product $product, ProductSku $sku)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:product_skus,sku,' . $sku->id,
            'name' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $sku->update($validated);

        return redirect()->route('products.skus.index', $product)->with('success', 'SKU更新成功');
    }

    public function destroy(Product $product, ProductSku $sku)
    {
        $sku->delete();
        return redirect()->route('products.skus.index', $product)->with('success', 'SKU删除成功');
    }
}
