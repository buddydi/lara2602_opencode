@extends('admin_layout')

@section('title', '退款详情')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>退款详情</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="150">ID</th>
                <td>{{ $refund->id }}</td>
            </tr>
            <tr>
                <th>订单号</th>
                <td>{{ $refund->order->order_no }}</td>
            </tr>
            <tr>
                <th>客户</th>
                <td>{{ $refund->customer->name }} ({{ $refund->customer->email }})</td>
            </tr>
            <tr>
                <th>退款金额</th>
                <td style="color: #e4393c; font-weight: bold;">¥{{ $refund->amount }}</td>
            </tr>
            <tr>
                <th>退款原因</th>
                <td>{{ $refund->reason }}</td>
            </tr>
            <tr>
                <th>详细说明</th>
                <td>{{ $refund->description ?: '无' }}</td>
            </tr>
            <tr>
                <th>状态</th>
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
            </tr>
            <tr>
                <th>申请时间</th>
                <td>{{ $refund->created_at }}</td>
            </tr>
            @if($refund->processed_at)
            <tr>
                <th>处理时间</th>
                <td>{{ $refund->processed_at }}</td>
            </tr>
            @endif
            @if($refund->reject_reason)
            <tr>
                <th>拒绝原因</th>
                <td style="color: #dc3545;">{{ $refund->reject_reason }}</td>
            </tr>
            @endif
        </table>
        
        @if($refund->status === 'pending')
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <form method="POST" action="{{ route('admin.refunds.approve', $refund) }}">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('确定通过此退款申请？')">通过退款</button>
            </form>
            <form method="POST" action="{{ route('admin.refunds.reject', $refund) }}" style="display: inline-flex; gap: 10px;">
                @csrf
                <input type="text" name="reject_reason" placeholder="请输入拒绝原因" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <button type="submit" class="btn btn-danger" onclick="return confirm('确定拒绝此退款申请？')">拒绝退款</button>
            </form>
        </div>
        @endif
        
        <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary mt-3">返回列表</a>
    </div>
</div>
@endsection
