<?php $currentRoute = 'product-categories'; ?>
@extends('admin_layout')

@section('title', '商品分类管理')

@section('content')
<div class="section-title">商品分类管理</div>

<a href="{{ route('product-categories.create') }}" class="btn btn-primary">新建分类</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>Slug</th>
            <th>父分类</th>
            <th>商品数</th>
            <th>状态</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>
                {{ $category->name }}
                @if($category->children->count() > 0)
                    <span class="badge">{{ $category->children->count() }} 子类</span>
                @endif
            </td>
            <td>{{ $category->slug }}</td>
            <td>{{ $category->parent?->name ?: '-' }}</td>
            <td>{{ $category->products()->count() }}</td>
            <td>
                @if($category->is_active)
                    <span style="color: green;">启用</span>
                @else
                    <span style="color: red;">禁用</span>
                @endif
            </td>
            <td>{{ $category->order }}</td>
            <td>
                <a href="{{ route('product-categories.show', $category) }}" class="btn btn-outline">查看</a>
                <a href="{{ route('product-categories.edit', $category) }}" class="btn btn-secondary">编辑</a>
                <form action="{{ route('product-categories.destroy', $category) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除?')">删除</button>
                </form>
            </td>
        </tr>
        @if($category->children->count() > 0)
            @foreach($category->children as $child)
            <tr style="background: #f9f9f9;">
                <td>{{ $child->id }}</td>
                <td>└─ {{ $child->name }}</td>
                <td>{{ $child->slug }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $child->products()->count() }}</td>
                <td>
                    @if($child->is_active)
                        <span style="color: green;">启用</span>
                    @else
                        <span style="color: red;">禁用</span>
                    @endif
                </td>
                <td>{{ $child->order }}</td>
                <td>
                    <a href="{{ route('product-categories.edit', $child) }}" class="btn btn-secondary">编辑</a>
                    <form action="{{ route('product-categories.destroy', $child) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除?')">删除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        @endif
        @empty
        <tr>
            <td colspan="8" style="text-align: center;">暂无数据</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
