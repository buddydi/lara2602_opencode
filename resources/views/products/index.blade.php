<?php $currentRoute = 'products'; ?>
@extends('admin_layout')

@section('title', '商品管理')

@section('content')
<div class="section-title">商品管理</div>

<form method="get" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
    <select name="category_id" style="width: 150px;">
        <option value="">全部分类</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <select name="brand_id" style="width: 150px;">
        <option value="">全部品牌</option>
        @foreach($brands as $brand)
        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
        @endforeach
    </select>
    <select name="status" style="width: 150px;">
        <option value="">全部状态</option>
        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>草稿</option>
        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>已发布</option>
        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>已归档</option>
    </select>
    <button type="submit" class="btn btn-primary">筛选</button>
    <a href="{{ route('products.create') }}" class="btn btn-primary">新建商品</a>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>封面</th>
            <th>名称</th>
            <th>分类</th>
            <th>品牌</th>
            <th>价格</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>
                @if($product->cover_image)
                <img src="{{ asset('storage/' . $product->cover_image) }}" style="width: 50px; height: 50px; object-fit: cover;">
                @else
                -
                @endif
            </td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category?->name ?: '-' }}</td>
            <td>{{ $product->brand?->name ?: '-' }}</td>
            <td>¥{{ $product->price }}</td>
            <td>
                @switch($product->status)
                    @case('draft') <span>草稿</span> @break
                    @case('published') <span style="color: green;">已发布</span> @break
                    @case('archived') <span style="color: gray;">已归档</span> @break
                @endswitch
            </td>
            <td>
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline">查看</a>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary">编辑</a>
                <a href="{{ route('products.skus.index', $product) }}" class="btn btn-outline">SKU</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除?')">删除</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center;">暂无数据</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $products->links() }}
@endsection
