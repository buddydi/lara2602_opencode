@extends('front.layout')

@section('title', '我的退款')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">我的退款</h2>
    
    @if($refunds->isEmpty())
    <div style="text-align: center; padding: 50px; color: #999;">
        <p>暂无退款记录</p>
        <a href="{{ route('orders.index') }}" class="btn" style="margin-top: 15px;">查看订单</a>
    </div>
    @else
    <table class="cart-table">
        <thead>
            <tr>
                <th>订单号</th>
                <th>退款金额</th>
                <th>退款原因</th>
                <th>状态</th>
                <th>申请时间</th>
                <th>处理时间</th>
            </tr>
        </thead>
        <tbody>
            @foreach($refunds as $refund)
            <tr>
                <td>{{ $refund->order->order_no }}</td>
                <td style="color: #e4393c;">¥{{ $refund->amount }}</td>
                <td>{{ $refund->reason }}</td>
                <td>
                    @if($refund->status === 'pending')
                    <span style="color: #faa300;">待处理</span>
                    @elseif($refund->status === 'approved')
                    <span style="color: #28a745;">已退款</span>
                    @elseif($refund->status === 'rejected')
                    <span style="color: #dc3545;">已拒绝</span>
                    @endif
                </td>
                <td>{{ $refund->created_at }}</td>
                <td>{{ $refund->processed_at ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
