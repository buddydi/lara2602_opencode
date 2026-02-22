@extends('front.layout')

@section('title', '订单详情')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">订单详情</h2>
    
    <div style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
        <div><span style="color: #999;">订单编号：</span>{{ $order->order_no }}</div>
        <div><span style="color: #999;">订单状态：</span><span style="color: #e4393c;">{{ $order->status_text }}</span></div>
        <div><span style="color: #999;">下单时间：</span>{{ $order->created_at }}</div>
    </div>
    
    <h3 style="margin-bottom: 15px; font-size: 16px;">收货信息</h3>
    <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px;">
        <div>{{ $order->address->name }} {{ $order->address->phone }}</div>
        <div style="color: #666;">{{ $order->address->full_address }}</div>
    </div>
    
    <h3 style="margin-bottom: 15px; font-size: 16px;">商品信息</h3>
    <table class="cart-table">
        <thead>
            <tr>
                <th>商品</th>
                <th>规格</th>
                <th>单价</th>
                <th>数量</th>
                <th>小计</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ $item->product_image ? asset('storage/' . $item->product_image) : 'https://via.placeholder.com/50' }}" style="width: 50px; height: 50px; object-fit: cover;">
                        {{ $item->product_name }}
                    </div>
                </td>
                <td>{{ $item->sku_name ?: '-' }}</td>
                <td>¥{{ $item->price }}</td>
                <td>{{ $item->quantity }}</td>
                <td style="color: #e4393c;">¥{{ $item->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="text-align: right; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
        <div>商品总数：{{ $order->product_count }}</div>
        <div style="font-size: 20px; color: #e4393c; margin-top: 10px;">
            订单总价：¥{{ $order->total_amount }}
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        <a href="{{ route('orders.index') }}" class="btn btn-outline">返回订单列表</a>
    </div>
</div>
@endsection
