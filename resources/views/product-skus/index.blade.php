<?php $currentRoute = 'products'; ?>
@extends('admin_layout')

@section('title', 'SKU管理 - ' . $product->name)

@section('content')
<div class="section-title">SKU管理 - {{ $product->name }}</div>

<a href="{{ route('products.skus.create', $product) }}" class="btn btn-primary">新建SKU</a>
<a href="{{ route('products.index') }}" class="btn btn-secondary">返回商品列表</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>SKU编码</th>
            <th>名称</th>
            <th>价格</th>
            <th>库存</th>
            <th>销量</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse($skus as $sku)
        <tr>
            <td>{{ $sku->id }}</td>
            <td>{{ $sku->sku }}</td>
            <td>{{ $sku->name ?: '-' }}</td>
            <td>{{ $sku->price ? '¥' . $sku->price : '-' }}</td>
            <td>{{ $sku->stock }}</td>
            <td>{{ $sku->sales }}</td>
            <td>
                @if($sku->is_active)
                    <span style="color: green;">启用</span>
                @else
                    <span style="color: red;">禁用</span>
                @endif
            </td>
            <td>
                <a href="{{ route('products.skus.edit', [$product, $sku]) }}" class="btn btn-secondary">编辑</a>
                <form action="{{ route('products.skus.destroy', [$product, $sku]) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除?')">删除</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center;">暂无SKU，请添加</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
