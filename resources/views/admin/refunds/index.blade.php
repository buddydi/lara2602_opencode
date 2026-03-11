@extends('admin_layout')

@section('title', '退款管理')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>退款列表</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>订单号</th>
                    <th>客户</th>
                    <th>退款金额</th>
                    <th>退款原因</th>
                    <th>状态</th>
                    <th>申请时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($refunds as $refund)
                <tr>
                    <td>{{ $refund->id }}</td>
                    <td>{{ $refund->order->order_no }}</td>
                    <td>{{ $refund->customer->name }}</td>
                    <td>¥{{ $refund->amount }}</td>
                    <td>{{ $refund->reason }}</td>
                    <td>
                        @switch($refund->status)
                            @case('pending')
                                <span class="badge badge-warning">待审核</span>
                                @break
                            @case('approved')
                                <span class="badge badge-success">已退款</span>
                                @break
                            @case('rejected')
                                <span class="badge badge-danger">已拒绝</span>
                                @break
                            @default
                                {{ $refund->status }}
                        @endswitch
                    </td>
                    <td>{{ $refund->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.refunds.show', $refund) }}" class="btn btn-sm btn-info">查看</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $refunds->links() }}
    </div>
</div>
@endsection
