@extends('admin_layout')

@section('title', '出入库记录')

@section('content')
<div class="page-header">
    <h1>出入库记录</h1>
    <div>
        <a href="{{ route('admin.stock.index') }}" class="btn btn-outline">库存管理</a>
        <a href="{{ route('admin.stock.alerts') }}" class="btn btn-outline">预警配置</a>
        <a href="{{ route('admin.stock.create-log') }}" class="btn">出入库操作</a>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <select name="type" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部类型</option>
            <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>入库</option>
            <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>出库</option>
            <option value="adjust" {{ request('type') === 'adjust' ? 'selected' : '' }}>调整</option>
        </select>
        <input type="date" name="start_date" value="{{ request('start_date') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        <span>至</span>
        <input type="date" name="end_date" value="{{ request('end_date') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        <button type="submit" class="btn">筛选</button>
        <a href="{{ route('admin.stock.logs') }}" class="btn btn-outline">重置</a>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>时间</th>
            <th>商品</th>
            <th>SKU</th>
            <th>类型</th>
            <th>数量</th>
            <th>库存</th>
            <th>原因</th>
            <th>操作人</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->product->name }}</td>
            <td>{{ $log->sku?->name ?: $log->sku?->sku ?: '-' }}</td>
            <td>
                @switch($log->type)
                    @case('in')
                    <span class="badge badge-success">入库</span>
                    @break
                    @case('out')
                    <span class="badge badge-warning">出库</span>
                    @break
                    @case('adjust')
                    <span class="badge badge-info">调整</span>
                    @break
                @endswitch
            </td>
            <td>
                @if($log->quantity > 0)
                <span style="color: #28a745;">+{{ $log->quantity }}</span>
                @else
                <span style="color: #dc3545;">{{ $log->quantity }}</span>
                @endif
            </td>
            <td>{{ $log->balance }}</td>
            <td>{{ \App\Models\StockLog::getReasonOptions()[$log->reason] ?? $log->reason }}</td>
            <td>{{ $log->user?->name ?: '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $logs->links() }}
</div>
@endsection
