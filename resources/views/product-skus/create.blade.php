<?php $currentRoute = 'products'; ?>
@extends('admin_layout')

@section('title', '新建SKU')

@section('content')
<div class="section-title">新建SKU - {{ $product->name }}</div>

<form action="{{ route('products.skus.store', $product) }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label>SKU编码 *</label>
        <input type="text" name="sku" required placeholder="如：IPHONE15-128-BLK">
    </div>
    
    <div class="form-group">
        <label>SKU名称</label>
        <input type="text" name="name" placeholder="如：iPhone 15 128G 黑色">
    </div>
    
    <div class="form-group">
        <label>价格</label>
        <input type="number" name="price" step="0.01" placeholder="留空则使用商品价格">
    </div>
    
    <div class="form-group">
        <label>库存 *</label>
        <input type="number" name="stock" value="0" required>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" checked> 启用
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary">创建</button>
    <a href="{{ route('products.skus.index', $product) }}" class="btn btn-secondary">返回</a>
</form>
@endsection
