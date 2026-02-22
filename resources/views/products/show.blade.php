<?php $currentRoute = 'products'; ?>
@extends('admin_layout')

@section('title', '商品详情')

@section('content')
<div class="section-title">商品详情</div>

<table>
    <tr>
        <th>ID</th>
        <td>{{ $product->id }}</td>
    </tr>
    <tr>
        <th>名称</th>
        <td>{{ $product->name }}</td>
    </tr>
    <tr>
        <th>分类</th>
        <td>{{ $product->category?->name ?: '-' }}</td>
    </tr>
    <tr>
        <th>品牌</th>
        <td>{{ $product->brand?->name ?: '-' }}</td>
    </tr>
    <tr>
        <th>价格</th>
        <td>¥{{ $product->price }}</td>
    </tr>
    <tr>
        <th>原价</th>
        <td>{{ $product->original_price ? '¥' . $product->original_price : '-' }}</td>
    </tr>
    <tr>
        <th>封面</th>
        <td>
            @if($product->cover_image)
            <img src="{{ asset('storage/' . $product->cover_image) }}" style="width: 150px;">
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>状态</th>
        <td>{{ $product->status }}</td>
    </tr>
    <tr>
        <th>创建时间</th>
        <td>{{ $product->created_at }}</td>
    </tr>
</table>

<a href="{{ route('products.edit', $product) }}" class="btn btn-primary">编辑</a>
<a href="{{ route('products.skus.index', $product) }}" class="btn btn-primary">管理SKU</a>
<a href="{{ route('products.index') }}" class="btn btn-secondary">返回</a>
@endsection
