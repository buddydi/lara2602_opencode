<?php $currentRoute = 'product-categories'; ?>
@extends('admin_layout')

@section('title', '商品分类详情')

@section('content')
<div class="section-title">商品分类详情</div>

<table>
    <tr>
        <th>ID</th>
        <td>{{ $productCategory->id }}</td>
    </tr>
    <tr>
        <th>名称</th>
        <td>{{ $productCategory->name }}</td>
    </tr>
    <tr>
        <th>Slug</th>
        <td>{{ $productCategory->slug }}</td>
    </tr>
    <tr>
        <th>上级分类</th>
        <td>{{ $productCategory->parent?->name ?: '顶级分类' }}</td>
    </tr>
    <tr>
        <th>描述</th>
        <td>{{ $productCategory->description ?: '-' }}</td>
    </tr>
    <tr>
        <th>分类图片</th>
        <td>
            @if($productCategory->image)
            <img src="{{ asset('storage/' . $productCategory->image) }}" style="max-width: 200px;">
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>状态</th>
        <td>{{ $productCategory->is_active ? '启用' : '禁用' }}</td>
    </tr>
    <tr>
        <th>排序</th>
        <td>{{ $productCategory->order }}</td>
    </tr>
    <tr>
        <th>商品数</th>
        <td>{{ $productCategory->products()->count() }}</td>
    </tr>
    <tr>
        <th>创建时间</th>
        <td>{{ $productCategory->created_at }}</td>
    </tr>
</table>

<a href="{{ route('product-categories.edit', $productCategory) }}" class="btn btn-primary">编辑</a>
<a href="{{ route('product-categories.index') }}" class="btn btn-secondary">返回</a>
@endsection
