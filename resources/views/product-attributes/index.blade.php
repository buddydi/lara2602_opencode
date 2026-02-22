<?php $currentRoute = 'product-attributes'; ?>
@extends('admin_layout')

@section('title', '商品属性管理')

@section('content')
<div class="section-title">商品属性管理</div>

<a href="{{ route('product-attributes.create') }}" class="btn btn-primary">新建属性</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>编码</th>
            <th>类型</th>
            <th>必填</th>
            <th>可筛选</th>
            <th>属性值</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attributes as $attr)
        <tr>
            <td>{{ $attr->id }}</td>
            <td>{{ $attr->name }}</td>
            <td>{{ $attr->code }}</td>
            <td>
                @switch($attr->type)
                    @case('select') 规格 @break
                    @case('text') 文本 @break
                    @case('number') 数字 @break
                @endswitch
            </td>
            <td>{{ $attr->is_required ? '是' : '否' }}</td>
            <td>{{ $attr->is_filterable ? '是' : '否' }}</td>
            <td>
                @foreach($attr->values as $val)
                    <span class="badge">{{ $val->value }}</span>
                @endforeach
            </td>
            <td>
                <a href="{{ route('product-attributes.edit', $attr) }}" class="btn btn-secondary">编辑</a>
                <form action="{{ route('product-attributes.destroy', $attr) }}" method="POST" style="display:inline;">
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
@endsection
