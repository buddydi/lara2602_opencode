<?php $currentRoute = 'brands'; ?>
@extends('admin_layout')

@section('title', '品牌详情')

@section('content')
<div class="section-title">品牌详情</div>

<table>
    <tr>
        <th>ID</th>
        <td>{{ $brand->id }}</td>
    </tr>
    <tr>
        <th>名称</th>
        <td>{{ $brand->name }}</td>
    </tr>
    <tr>
        <th>Slug</th>
        <td>{{ $brand->slug }}</td>
    </tr>
    <tr>
        <th>Logo</th>
        <td>
            @if($brand->logo)
            <img src="{{ asset('storage/' . $brand->logo) }}" style="width: 100px;">
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>描述</th>
        <td>{{ $brand->description ?: '-' }}</td>
    </tr>
    <tr>
        <th>状态</th>
        <td>{{ $brand->is_active ? '启用' : '禁用' }}</td>
    </tr>
    <tr>
        <th>商品数</th>
        <td>{{ $brand->products()->count() }}</td>
    </tr>
    <tr>
        <th>创建时间</th>
        <td>{{ $brand->created_at }}</td>
    </tr>
</table>

<a href="{{ route('brands.edit', $brand) }}" class="btn btn-primary">编辑</a>
<a href="{{ route('brands.index') }}" class="btn btn-secondary">返回</a>
@endsection
