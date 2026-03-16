@extends('admin_layout')

@section('title', '库存管理')

@section('content')
<div class="page-header">
    <h1>库存管理</h1>
    <div>
        <a href="{{ route('admin.stock.logs') }}" class="btn btn-outline">出入库记录</a>
        <a href="{{ route('admin.stock.alerts') }}" class="btn btn-outline">预警配置</a>
        <a href="{{ route('admin.stock.create-log') }}" class="btn">出入库操作</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 20px;">
    <div class="card" style="text-align: center; padding: 20px;">
        <div style="font-size: 24px; font-weight: bold;">{{ $stats['total'] }}</div>
        <div style="color: #666;">总SKU</div>
    </div>
    <div class="card" style="text-align: center; padding: 20px; border: 2px solid #dc3545;">
        <div style="font-size: 24px; font-weight: bold; color: #dc3545;">{{ $stats['out'] }}</div>
        <div style="color: #dc3545;">缺货</div>
    </div>
    <div class="card" style="text-align: center; padding: 20px; border: 2px solid #ffc107;">
        <div style="font-size: 24px; font-weight: bold; color: #ffc107;">{{ $stats['critical'] }}</div>
        <div style="color: #ffc107;">紧急</div>
    </div>
    <div class="card" style="text-align: center; padding: 20px; border: 2px solid #faa300;">
        <div style="font-size: 24px; font-weight: bold; color: #faa300;">{{ $stats['low'] }}</div>
        <div style="color: #faa300;">低库存</div>
    </div>
    <div class="card" style="text-align: center; padding: 20px; border: 2px solid #28a745;">
        <div style="font-size: 24px; font-weight: bold; color: #28a745;">{{ $stats['normal'] }}</div>
        <div style="color: #28a745;">正常</div>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <input type="text" name="product_name" value="{{ request('product_name') }}" placeholder="商品名称" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        <select name="stock_status" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部状态</option>
            <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>缺货</option>
            <option value="critical" {{ request('stock_status') === 'critical' ? 'selected' : '' }}>紧急</option>
            <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>低库存</option>
            <option value="normal" {{ request('stock_status') === 'normal' ? 'selected' : '' }}>正常</option>
        </select>
        <button type="submit" class="btn">筛选</button>
        <a href="{{ route('admin.stock.index') }}" class="btn btn-outline">重置</a>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>商品</th>
            <th>SKU</th>
            <th>库存</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($skus as $sku)
        <tr>
            <td>{{ $sku->product->name }}</td>
            <td>{{ $sku->name ?: $sku->sku }}</td>
            <td>
                @if($sku->stock <= 0)
                <span style="color: #dc3545; font-weight: bold;">{{ $sku->stock }}</span>
                @elseif($sku->stock <= 5)
                <span style="color: #ffc107; font-weight: bold;">{{ $sku->stock }}</span>
                @elseif($sku->stock <= 10)
                <span style="color: #faa300;">{{ $sku->stock }}</span>
                @else
                {{ $sku->stock }}
                @endif
            </td>
            <td>
                @if($sku->stock <= 0)
                <span class="badge badge-danger">缺货</span>
                @elseif($sku->stock <= 5)
                <span class="badge badge-warning">紧急</span>
                @elseif($sku->stock <= 10)
                <span style="color: #faa300;">低库存</span>
                @else
                <span class="badge badge-success">正常</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.stock.create-log') }}?product_id={{ $sku->product_id }}" class="btn btn-sm">调整库存</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $skus->links() }}
</div>
@endsection
