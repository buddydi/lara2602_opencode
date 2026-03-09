@extends('admin_layout')

@section('title', '客户详情')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>客户信息</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="150">ID</th>
                <td>{{ $customer->id }}</td>
            </tr>
            <tr>
                <th>姓名</th>
                <td>{{ $customer->name }}</td>
            </tr>
            <tr>
                <th>邮箱</th>
                <td>{{ $customer->email }}</td>
            </tr>
            <tr>
                <th>手机</th>
                <td>{{ $customer->phone ?: '-' }}</td>
            </tr>
            <tr>
                <th>注册时间</th>
                <td>{{ $customer->created_at }}</td>
            </tr>
        </table>
        
        <h4 class="mt-4">收货地址</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>姓名</th>
                    <th>电话</th>
                    <th>地址</th>
                    <th>默认</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customer->addresses as $address)
                <tr>
                    <td>{{ $address->name }}</td>
                    <td>{{ $address->phone }}</td>
                    <td>{{ $address->province }}{{ $address->city }}{{ $address->district }}{{ $address->detail_address }}</td>
                    <td>{{ $address->is_default ? '是' : '否' }}</td>
                </tr>
                @empty
                <tr><td colspan="4">暂无地址</td></tr>
                @endforelse
            </tbody>
        </table>
        
        <h4 class="mt-4">订单记录</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>订单号</th>
                    <th>金额</th>
                    <th>状态</th>
                    <th>时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customer->orders as $order)
                <tr>
                    <td>{{ $order->order_no }}</td>
                    <td>¥{{ $order->pay_amount }}</td>
                    <td>{{ $order->status_text }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">查看</a></td>
                </tr>
                @empty
                <tr><td colspan="5">暂无订单</td></tr>
                @endforelse
            </tbody>
        </table>
        
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary mt-3">返回列表</a>
    </div>
</div>
@endsection
