@extends('admin_layout')

@section('title', '数据统计')

@section('content')
<div class="page-header">
    <h1>数据统计</h1>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 8px; color: #fff;">
        <div style="font-size: 14px; opacity: 0.9;">总订单数</div>
        <div style="font-size: 32px; font-weight: bold; margin: 10px 0;">{{ $orderCount }}</div>
        <div style="font-size: 12px; opacity: 0.8;">总销售额 ¥{{ number_format($orderAmount, 2) }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 8px; color: #fff;">
        <div style="font-size: 14px; opacity: 0.9;">今日订单</div>
        <div style="font-size: 32px; font-weight: bold; margin: 10px 0;">{{ $todayOrders }}</div>
        <div style="font-size: 12px; opacity: 0.8;">销售额 ¥{{ number_format($todayAmount, 2) }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 8px; color: #fff;">
        <div style="font-size: 14px; opacity: 0.9;">本月订单</div>
        <div style="font-size: 32px; font-weight: bold; margin: 10px 0;">{{ $monthOrders }}</div>
        <div style="font-size: 12px; opacity: 0.8;">销售额 ¥{{ number_format($monthAmount, 2) }}</div>
    </div>
    <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 20px; border-radius: 8px; color: #fff;">
        <div style="font-size: 14px; opacity: 0.9;">客户/商品</div>
        <div style="font-size: 32px; font-weight: bold; margin: 10px 0;">{{ $customerCount }} / {{ $productCount }}</div>
        <div style="font-size: 12px; opacity: 0.8;">待退款 {{ $pendingRefunds }} 单</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h3>订单状态分布</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                <div style="padding: 10px 15px; background: #f0f0f0; border-radius: 4px;">
                    待支付: <strong>{{ $ordersByStatus['pending'] ?? 0 }}</strong>
                </div>
                <div style="padding: 10px 15px; background: #e3f2fd; border-radius: 4px;">
                    已支付: <strong>{{ $ordersByStatus['paid'] ?? 0 }}</strong>
                </div>
                <div style="padding: 10px 15px; background: #fff3e0; border-radius: 4px;">
                    已发货: <strong>{{ $ordersByStatus['shipped'] ?? 0 }}</strong>
                </div>
                <div style="padding: 10px 15px; background: #e8f5e9; border-radius: 4px;">
                    已完成: <strong>{{ $ordersByStatus['completed'] ?? 0 }}</strong>
                </div>
                <div style="padding: 10px 15px; background: #ffebee; border-radius: 4px;">
                    已取消: <strong>{{ $ordersByStatus['cancelled'] ?? 0 }}</strong>
                </div>
                <div style="padding: 10px 15px; background: #fce4ec; border-radius: 4px;">
                    已退款: <strong>{{ $ordersByStatus['refunded'] ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>热销商品 TOP10</h3>
        </div>
        <div class="card-body">
            @if($topProducts->isEmpty())
            <p style="color: #999; text-align: center;">暂无数据</p>
            @else
            <table class="table">
                <thead>
                    <tr>
                        <th>商品</th>
                        <th>销量</th>
                        <th>销售额</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->total_qty }}</td>
                        <td>¥{{ number_format($product->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3>最近订单</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>订单号</th>
                    <th>客户</th>
                    <th>金额</th>
                    <th>状态</th>
                    <th>时间</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_no }}</a></td>
                    <td>{{ $order->customer->name }}</td>
                    <td>¥{{ $order->pay_amount }}</td>
                    <td>
                        @switch($order->status)
                            @case('pending')
                            <span class="badge badge-warning">待支付</span>
                            @break
                            @case('paid')
                            <span class="badge badge-info">已支付</span>
                            @break
                            @case('shipped')
                            <span class="badge badge-primary">已发货</span>
                            @break
                            @case('completed')
                            <span class="badge badge-success">已完成</span>
                            @break
                            @case('cancelled')
                            <span class="badge badge-danger">已取消</span>
                            @break
                            @case('refunded')
                            <span class="badge badge-danger">已退款</span>
                            @break
                        @endswitch
                    </td>
                    <td>{{ $order->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
