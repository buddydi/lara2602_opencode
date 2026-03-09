@extends('admin_layout')

@section('title', '订单管理')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>订单列表</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>订单号</th>
                    <th>客户</th>
                    <th>收货人</th>
                    <th>金额</th>
                    <th>状态</th>
                    <th>下单时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_no }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->address->name }}</td>
                    <td>¥{{ $order->pay_amount }}</td>
                    <td>
                        @switch($order->status)
                            @case('pending') <span class="badge badge-warning">待支付</span> @break
                            @case('paid') <span class="badge badge-info">已支付</span> @break
                            @case('shipped') <span class="badge badge-primary">已发货</span> @break
                            @case('completed') <span class="badge badge-success">已完成</span> @break
                            @case('cancelled') <span class="badge badge-secondary">已取消</span> @break
                            @default {{ $order->status }}
                        @endswitch
                    </td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">查看</a>
                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('确定删除？')">删除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection
