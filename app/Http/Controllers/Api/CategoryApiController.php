<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $categories = Category::with('parent', 'children')
            ->when($request->parent_id, function ($query) use ($request) {
                $query->where('parent_id', $request->parent_id);
            })
            ->when(!$request->parent_id && $request->get('flat') !== 'true', function ($query) {
                $query->whereNull('parent_id');
            })
            ->orderBy('order')
            ->get();

        return $this->success($categories);
    }

    public function tree()
    {
        return $this->success(Category::tree());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        return $this->success($category, '分类创建成功', 201);
    }

    public function show(Category $category)
    {
        return $this->success($category->load(['parent', 'children', 'posts']));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return $this->success($category, '分类更新成功');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return $this->error('请先删除子分类', 422);
        }

        if ($category->posts()->count() > 0) {
            return $this->error('该分类下还有文章，无法删除', 422);
        }

        $category->delete();

        return $this->success(null, '分类删除成功');
    }
}
