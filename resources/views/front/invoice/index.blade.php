@extends('front.layout')

@section('title', '我的发票')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">我的发票</h2>
    
    @if($invoices->isEmpty())
    <div style="text-align: center; padding: 50px; color: #999;">
        <p>暂无发票记录</p>
        <a href="{{ route('orders.index') }}" class="btn" style="margin-top: 15px;">查看订单</a>
    </div>
    @else
    <table class="cart-table">
        <thead>
            <tr>
                <th>发票号</th>
                <th>订单号</th>
                <th>类型</th>
                <th>抬头</th>
                <th>金额</th>
                <th>状态</th>
                <th>申请时间</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_no ?: '-' }}</td>
                <td>{{ $invoice->order->order_no }}</td>
                <td>{{ $invoice->type === 'company' ? '企业' : '个人' }}</td>
                <td>{{ $invoice->title }}</td>
                <td style="color: #e4393c;">¥{{ $invoice->amount }}</td>
                <td>
                    @if($invoice->status === 'pending')
                    <span style="color: #faa300;">待开</span>
                    @elseif($invoice->status === 'issued')
                    <span style="color: #28a745;">已开</span>
                    @endif
                </td>
                <td>{{ $invoice->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
