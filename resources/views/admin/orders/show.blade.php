@extends('admin_layout')

@section('title', '订单详情')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>订单信息</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="150">订单号</th>
                <td>{{ $order->order_no }}</td>
            </tr>
            <tr>
                <th>客户</th>
                <td>{{ $order->customer->name }} ({{ $order->customer->email }})</td>
            </tr>
            <tr>
                <th>订单状态</th>
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
            </tr>
            <tr>
                <th>支付方式</th>
                <td>
                    @switch($order->pay_method)
                        @case('alipay') 支付宝 @break
                        @case('wechat') 微信支付 @break
                        @case('balance') 余额支付 @break
                        @default -
                    @endswitch
                </td>
            </tr>
            <tr>
                <th>支付时间</th>
                <td>{{ $order->paid_at ?: '-' }}</td>
            </tr>
        </table>
        
        <h4 class="mt-4">收货信息</h4>
        <table class="table table-bordered">
            <tr>
                <th>收货人</th>
                <td>{{ $order->address->name }} ({{ $order->address->phone }})</td>
            </tr>
            <tr>
                <th>地址</th>
                <td>{{ $order->address->province }}{{ $order->address->city }}{{ $order->address->district }}{{ $order->address->detail_address }}</td>
            </tr>
        </table>
        
        <h4 class="mt-4">物流信息</h4>
        @if($order->status === 'paid' || $order->status === 'shipped' || $order->status === 'completed')
        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="form-inline">
            @csrf
            @method('PUT')
            <div class="form-group mr-2">
                <label>快递公司：</label>
                <select name="shipping_company" class="form-control">
                    <option value="顺丰速运" {{ $order->shipping_company == '顺丰速运' ? 'selected' : '' }}>顺丰速运</option>
                    <option value="圆通速递" {{ $order->shipping_company == '圆通速递' ? 'selected' : '' }}>圆通速递</option>
                    <option value="中通快递" {{ $order->shipping_company == '中通快递' ? 'selected' : '' }}>中通快递</option>
                    <option value="申通快递" {{ $order->shipping_company == '申通快递' ? 'selected' : '' }}>申通快递</option>
                    <option value="韵达速递" {{ $order->shipping_company == '韵达速递' ? 'selected' : '' }}>韵达速递</option>
                    <option value="EMS" {{ $order->shipping_company == 'EMS' ? 'selected' : '' }}>EMS</option>
                </select>
            </div>
            <div class="form-group mr-2">
                <label>快递单号：</label>
                <input type="text" name="shipping_no" class="form-control" value="{{ $order->shipping_no }}" placeholder="请输入快递单号">
            </div>
            <button type="submit" class="btn btn-primary">{{ $order->shipping_no ? '更新物流' : '发货' }}</button>
        </form>
        @endif
        
        @if($order->shipping_no)
        <div class="mt-3">
            <strong>快递公司：</strong>{{ $order->shipping_company }} &nbsp;&nbsp;
            <strong>快递单号：</strong>{{ $order->shipping_no }} &nbsp;&nbsp;
            <strong>发货时间：</strong>{{ $order->shipped_at }}
        </div>
        @endif
        
        <h4 class="mt-4">商品信息</h4>
        <table class="table table-bordered">
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
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->sku_name ?: '-' }}</td>
                    <td>¥{{ $item->price }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>¥{{ $item->total }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>商品总价：</strong></td>
                    <td>¥{{ $order->total_amount }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>运费：</strong></td>
                    <td>¥{{ $order->shipping_fee }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>应付总额：</strong></td>
                    <td><strong>¥{{ $order->pay_amount }}</strong></td>
                </tr>
            </tfoot>
        </table>
        
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">返回列表</a>
    </div>
</div>
@endsection
