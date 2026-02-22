<?php $currentRoute = 'products'; ?>
@extends('admin_layout')

@section('title', '编辑SKU')

@section('content')
<div class="section-title">编辑SKU - {{ $product->name }}</div>

<form action="{{ route('products.skus.update', [$product, $sku]) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-group">
        <label>SKU编码 *</label>
        <input type="text" name="sku" value="{{ $sku->sku }}" required>
    </div>
    
    <div class="form-group">
        <label>SKU名称</label>
        <input type="text" name="name" value="{{ $sku->name }}">
    </div>
    
    <div class="form-group">
        <label>价格</label>
        <input type="number" name="price" step="0.01" value="{{ $sku->price }}">
    </div>
    
    <div class="form-group">
        <label>库存 *</label>
        <input type="number" name="stock" value="{{ $sku->stock }}" required>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" value="1" {{ $sku->is_active ? 'checked' : '' }}> 启用
        </label>
    </div>
    
    <button type="submit" class="btn btn-primary">更新</button>
    <a href="{{ route('products.skus.index', $product) }}" class="btn btn-secondary">返回</a>
</form>
@endsection
