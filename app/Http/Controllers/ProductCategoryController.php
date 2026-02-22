<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with('parent', 'children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
        return view('product-categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = ProductCategory::flatTree();
        return view('product-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $slug = Str::slug($validated['name']);
        if (ProductCategory::where('slug', $slug)->exists()) {
            return back()->withInput()->with('error', '分类名称已存在，请使用其他名称');
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request->file('image'));
        }

        ProductCategory::create($validated);

        return redirect()->route('product-categories.index')->with('success', '商品分类创建成功');
    }

    public function show(ProductCategory $productCategory)
    {
        return view('product-categories.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory)
    {
        $categories = ProductCategory::flatTree()->except($productCategory->id);
        return view('product-categories.edit', compact('productCategory', 'categories'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        if (isset($validated['name'])) {
            $slug = Str::slug($validated['name']);
            if (ProductCategory::where('slug', $slug)->where('id', '!=', $productCategory->id)->exists()) {
                return back()->withInput()->with('error', '分类名称已存在，请使用其他名称');
            }
            $validated['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            if ($productCategory->image) {
                \Storage::disk('public')->delete($productCategory->image);
            }
            $validated['image'] = $this->uploadImage($request->file('image'));
        }

        $productCategory->update($validated);

        return redirect()->route('product-categories.index')->with('success', '商品分类更新成功');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->children()->count() > 0) {
            return redirect()->route('product-categories.index')->with('error', '请先删除子分类');
        }

        if ($productCategory->products()->count() > 0) {
            return redirect()->route('product-categories.index')->with('error', '该分类下还有商品，无法删除');
        }

        $productCategory->delete();
        return redirect()->route('product-categories.index')->with('success', '商品分类删除成功');
    }

    protected function uploadImage($image)
    {
        return $image->store('product-categories', 'public');
    }
}
