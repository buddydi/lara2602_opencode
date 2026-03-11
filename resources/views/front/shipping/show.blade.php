@extends('front.layout')

@section('title', '物流跟踪')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <a href="{{ route('orders.show', $order) }}" style="color: #666; text-decoration: none;">&lt; 返回订单详情</a>
    
    <h2 style="margin: 20px 0;">物流信息</h2>
    
    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span style="color: #999;">物流公司：</span>
            <span>{{ \App\Models\ShippingCompany::getCodeName($order->shipping_company) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span style="color: #999;">快递单号：</span>
            <span>{{ $order->shipping_no }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span style="color: #999;">发货时间：</span>
            <span>{{ $order->shipped_at }}</span>
        </div>
    </div>
    
    <h3 style="margin-bottom: 15px;">物流进度</h3>
    
    @if(empty($traces))
    <div style="text-align: center; padding: 50px; color: #999;">
        <p>暂无物流信息</p>
    </div>
    @else
    <div style="border-left: 2px solid #ddd; padding-left: 20px; margin-left: 10px;">
        @foreach($traces as $trace)
        <div style="position: relative; padding-bottom: 30px;">
            <div style="position: absolute; left: -26px; top: 0; width: 10px; height: 10px; background: #e4393c; border-radius: 50%;"></div>
            <div style="color: #999; font-size: 12px;">{{ $trace['time'] }}</div>
            <div style="font-weight: bold; margin-top: 5px;">{{ $trace['status'] }}</div>
            <div style="color: #666; margin-top: 5px;">{{ $trace['content'] }}</div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
