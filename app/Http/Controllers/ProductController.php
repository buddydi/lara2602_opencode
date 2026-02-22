<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->when(request('category_id'), fn($q) => $q->where('category_id', request('category_id')))
            ->when(request('brand_id'), fn($q) => $q->where('brand_id', request('brand_id')))
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->orderBy('id', 'desc')
            ->paginate(20);
        
        $categories = ProductCategory::flatTree();
        $brands = Brand::where('is_active', true)->get();
        
        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = ProductCategory::flatTree();
        $brands = Brand::where('is_active', true)->get();
        $attributes = ProductAttribute::with('values')->get();
        
        return view('products.create', compact('categories', 'brands', 'attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'content' => 'nullable',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:draft,published,archived',
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            return back()->withInput()->with('error', '商品名称已存在，请使用其他名称');
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        $product = Product::create($validated);

        $this->saveAttributeValues($product, $request);

        return redirect()->route('products.index')->with('success', '商品创建成功');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'skus', 'attributeValues.attribute', 'attributeValues.attributeValue']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::flatTree();
        $brands = Brand::where('is_active', true)->get();
        $attributes = ProductAttribute::with('values')->get();
        $product->load('attributeValues.attributeValue');
        
        return view('products.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'content' => 'nullable',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:draft,published,archived',
        ]);

        if (isset($validated['name'])) {
            $slug = Str::slug($validated['name']);
            if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                return back()->withInput()->with('error', '商品名称已存在，请使用其他名称');
            }
            $validated['slug'] = $slug;
        }

        if ($request->hasFile('cover_image')) {
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
            }
            $validated['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        $product->update($validated);

        $this->saveAttributeValues($product, $request);

        return redirect()->route('products.index')->with('success', '商品更新成功');
    }

    public function destroy(Product $product)
    {
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }
        
        $product->delete();
        return redirect()->route('products.index')->with('success', '商品删除成功');
    }

    protected function uploadImage($image)
    {
        return $image->store('products', 'public');
    }

    protected function saveAttributeValues(Product $product, Request $request)
    {
        $existingIds = [];
        
        if ($request->has('attribute_values')) {
            foreach ($request->attribute_values as $attributeId => $valueIds) {
                foreach ($valueIds as $valueId) {
                    $existing = ProductAttributeValue::where([
                        'product_id' => $product->id,
                        'attribute_id' => $attributeId,
                        'attribute_value_id' => $valueId,
                    ])->first();
                    
                    if (!$existing) {
                        $newRecord = ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $valueId,
                            'text_value' => null,
                        ]);
                        $existingIds[] = $newRecord->id;
                    } else {
                        $existingIds[] = $existing->id;
                    }
                }
            }
        }

        if ($request->has('attribute_text')) {
            foreach ($request->attribute_text as $attributeId => $textValue) {
                if (!empty($textValue)) {
                    $existing = ProductAttributeValue::where([
                        'product_id' => $product->id,
                        'attribute_id' => $attributeId,
                    ])->whereNotNull('text_value')->first();
                    
                    if ($existing) {
                        $existing->update(['text_value' => $textValue]);
                        $existingIds[] = $existing->id;
                    } else {
                        $newRecord = ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => null,
                            'text_value' => $textValue,
                        ]);
                        $existingIds[] = $newRecord->id;
                    }
                }
            }
        }
        
        if (!empty($existingIds)) {
            ProductAttributeValue::where('product_id', $product->id)
                ->whereNotIn('id', $existingIds)
                ->delete();
        }
    }
}
