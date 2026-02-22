<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $attributes = ProductAttribute::with('values')->orderBy('order')->get();
        return view('product-attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('product-attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:select,text,number',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'order' => 'integer',
        ]);

        $code = Str::slug($validated['name']);
        if (ProductAttribute::where('code', $code)->exists()) {
            return back()->withInput()->with('error', '属性代码已存在，请使用其他名称');
        }
        $validated['code'] = $code;

        $attribute = ProductAttribute::create($validated);

        if ($attribute->type === 'select' && $request->has('values_text')) {
            $values = array_filter(array_map('trim', explode("\n", $request->values_text)));
            foreach ($values as $value) {
                if (!empty($value)) {
                    $attribute->values()->create([
                        'value' => $value,
                        'code' => Str::slug($value),
                    ]);
                }
            }
        }

        return redirect()->route('product-attributes.index')->with('success', '属性创建成功');
    }

    public function show(ProductAttribute $productAttribute)
    {
        return view('product-attributes.show', compact('productAttribute'));
    }

    public function edit(ProductAttribute $productAttribute)
    {
        return view('product-attributes.edit', compact('productAttribute'));
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:select,text,number',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'order' => 'integer',
        ]);

        if (isset($validated['name'])) {
            $code = Str::slug($validated['name']);
            if (ProductAttribute::where('code', $code)->where('id', '!=', $productAttribute->id)->exists()) {
                return back()->withInput()->with('error', '属性代码已存在，请使用其他名称');
            }
            $validated['code'] = $code;
        }

        $productAttribute->update($validated);

        if ($productAttribute->type === 'select' && $request->has('values_text')) {
            $values = array_filter(array_map('trim', explode("\n", $request->values_text)));
            $productAttribute->values()->delete();
            foreach ($values as $value) {
                if (!empty($value)) {
                    $productAttribute->values()->create([
                        'value' => $value,
                        'code' => Str::slug($value),
                    ]);
                }
            }
        }

        return redirect()->route('product-attributes.index')->with('success', '属性更新成功');
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        if ($productAttribute->products()->count() > 0) {
            return redirect()->route('product-attributes.index')->with('error', '该属性已被商品使用，无法删除');
        }

        $productAttribute->delete();
        return redirect()->route('product-attributes.index')->with('success', '属性删除成功');
    }
}
