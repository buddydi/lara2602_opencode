<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(20);
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($validated['name']);
        if (Brand::where('slug', $slug)->exists()) {
            return back()->withInput()->with('error', '品牌名称已存在，请使用其他名称');
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadImage($request->file('logo'));
        }

        Brand::create($validated);

        return redirect()->route('brands.index')->with('success', '品牌创建成功');
    }

    public function show(Brand $brand)
    {
        return view('brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['name'])) {
            $slug = Str::slug($validated['name']);
            if (Brand::where('slug', $slug)->where('id', '!=', $brand->id)->exists()) {
                return back()->withInput()->with('error', '品牌名称已存在，请使用其他名称');
            }
            $validated['slug'] = $slug;
        }

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $validated['logo'] = $this->uploadImage($request->file('logo'));
        }

        $brand->update($validated);

        return redirect()->route('brands.index')->with('success', '品牌更新成功');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return redirect()->route('brands.index')->with('error', '该品牌下还有商品，无法删除');
        }

        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();
        return redirect()->route('brands.index')->with('success', '品牌删除成功');
    }

    protected function uploadImage($image)
    {
        return $image->store('brands', 'public');
    }
}
