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
    
    <h3 style="margin-bottom: 15px; font-size: 16px;">配送信息</h3>
    <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px;">
        <div>配送方式：{{ $order->shipping_method === 'express' ? '快递配送' : '标准配送' }}</div>
        <div>运费：¥{{ $order->shipping_fee }}</div>
        @if($order->shipping_no)
        <div>快递单号：{{ $order->shipping_no }}</div>
        <div>发货时间：{{ $order->shipped_at }}</div>
        @endif
    </div>
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
        <div>商品总价：¥{{ $order->total_amount }}</div>
        <div>运费：¥{{ $order->shipping_fee }}</div>
        <div style="font-size: 20px; color: #e4393c; margin-top: 10px;">
            应付总额：¥{{ $order->pay_amount }}
        </div>
    </div>
    
    @if($order->status === 'completed')
    <div style="margin-top: 20px;">
        <h3 style="margin-bottom: 15px; font-size: 16px;">商品评价</h3>
        @php
            $reviewedItems = $order->reviews->pluck('order_item_id')->toArray();
        @endphp
        @foreach($order->items as $item)
            @if(!in_array($item->id, $reviewedItems))
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border: 1px solid #eee; border-radius: 4px; margin-bottom: 10px;">
                <div>
                    <span>{{ $item->product_name }}</span>
                </div>
                <a href="{{ route('orders.review.create', [$order, $item]) }}" class="btn btn-sm">评价</a>
            </div>
            @else
            <div style="padding: 10px; border: 1px solid #eee; border-radius: 4px; margin-bottom: 10px; background: #f9f9f9;">
                <span>{{ $item->product_name }}</span>
                <span style="color: #999; margin-left: 10px;">已评价</span>
            </div>
            @endif
        @endforeach
    </div>
    @endif
    
    <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
        @if($order->status === 'pending')
            <a href="{{ route('orders.pay', $order) }}" class="btn">立即支付</a>
            <form method="POST" action="{{ route('orders.cancel', $order) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline" onclick="return confirm('确定要取消订单吗？')">取消订单</button>
            </form>
        @elseif($order->status === 'paid')
            <form method="POST" action="{{ route('orders.cancel', $order) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline" onclick="return confirm('确定要取消订单吗？')">取消订单</button>
            </form>
        @elseif($order->status === 'shipped')
            <form method="POST" action="{{ route('orders.receive', $order) }}">
                @csrf
                <button type="submit" class="btn" onclick="return confirm('确定已收到货物吗？')">确认收货</button>
            </form>
        @elseif($order->status === 'completed')
            <span style="color: #28a745; padding: 5px 15px; border: 1px solid #28a745; border-radius: 4px;">已完成</span>
        @endif
        <a href="{{ route('orders.index') }}" class="btn btn-outline">返回订单列表</a>
    </div>
</div>
@endsection
