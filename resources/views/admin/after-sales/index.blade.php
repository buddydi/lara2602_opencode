@extends('admin_layout')

@section('title', '售后管理')

@section('content')
<div class="page-header">
    <h1>售后管理</h1>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <select name="type" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部类型</option>
            <option value="return" {{ request('type') === 'return' ? 'selected' : '' }}>退货</option>
            <option value="exchange" {{ request('type') === 'exchange' ? 'selected' : '' }}>换货</option>
        </select>
        <select name="status" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部状态</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>待处理</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>处理中</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>已完成</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>已拒绝</option>
        </select>
        <button type="submit" class="btn">筛选</button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>订单号</th>
            <th>客户</th>
            <th>类型</th>
            <th>原因</th>
            <th>状态</th>
            <th>申请时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($afterSales as $afterSale)
        <tr>
            <td>{{ $afterSale->id }}</td>
            <td><a href="{{ route('admin.orders.show', $afterSale->order) }}">{{ $afterSale->order->order_no }}</a></td>
            <td>{{ $afterSale->customer->name }}</td>
            <td>
                @if($afterSale->type === 'return')
                <span class="badge">退货</span>
                @else
                <span class="badge">换货</span>
                @endif
            </td>
            <td>{{ $afterSale->reason }}</td>
            <td>
                @switch($afterSale->status)
                    @case('pending')
                    <span class="badge badge-warning">待处理</span>
                    @break
                    @case('processing')
                    <span class="badge badge-info">处理中</span>
                    @break
                    @case('completed')
                    <span class="badge badge-success">已完成</span>
                    @break
                    @case('rejected')
                    <span class="badge badge-danger">已拒绝</span>
                    @break
                @endswitch
            </td>
            <td>{{ $afterSale->created_at }}</td>
            <td>
                <a href="{{ route('admin.after-sales.show', $afterSale) }}" class="btn btn-sm">处理</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $afterSales->links() }}
</div>
@endsection
