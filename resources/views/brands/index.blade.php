<?php $currentRoute = 'brands'; ?>
@extends('admin_layout')

@section('title', '品牌管理')

@section('content')
<div class="section-title">品牌管理</div>

<a href="{{ route('brands.create') }}" class="btn btn-primary">新建品牌</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Logo</th>
            <th>名称</th>
            <th>Slug</th>
            <th>商品数</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse($brands as $brand)
        <tr>
            <td>{{ $brand->id }}</td>
            <td>
                @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" style="width: 50px; height: 50px; object-fit: cover;">
                @else
                -
                @endif
            </td>
            <td>{{ $brand->name }}</td>
            <td>{{ $brand->slug }}</td>
            <td>{{ $brand->products()->count() }}</td>
            <td>
                @if($brand->is_active)
                    <span style="color: green;">启用</span>
                @else
                    <span style="color: red;">禁用</span>
                @endif
            </td>
            <td>
                <a href="{{ route('brands.show', $brand) }}" class="btn btn-outline">查看</a>
                <a href="{{ route('brands.edit', $brand) }}" class="btn btn-secondary">编辑</a>
                <form action="{{ route('brands.destroy', $brand) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('确定删除?')">删除</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center;">暂无数据</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $brands->links() }}
@endsection
