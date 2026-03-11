@extends('front.layout')

@section('title', '售后服务记录')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">售后服务记录</h2>
    
    @if($afterSales->count() > 0)
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f5f5f5;">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">售后单号</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">订单编号</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">类型</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">状态</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">申请时间</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($afterSales as $afterSale)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px;">{{ $afterSale->id }}</td>
                <td style="padding: 12px;">{{ $afterSale->order->order_no }}</td>
                <td style="padding: 12px;">
                    @if($afterSale->type === 'return')
                    <span style="color: #e4393c;">退货</span>
                    @else
                    <span style="color: #faa300;">换货</span>
                    @endif
                </td>
                <td style="padding: 12px;">
                    @if($afterSale->status === 'pending')
                    <span style="color: #faa300; padding: 2px 8px; border: 1px solid #faa300; border-radius: 4px;">待处理</span>
                    @elseif($afterSale->status === 'processing')
                    <span style="color: #007bff; padding: 2px 8px; border: 1px solid #007bff; border-radius: 4px;">处理中</span>
                    @elseif($afterSale->status === 'completed')
                    <span style="color: #28a745; padding: 2px 8px; border: 1px solid #28a745; border-radius: 4px;">已完成</span>
                    @elseif($afterSale->status === 'rejected')
                    <span style="color: #dc3545; padding: 2px 8px; border: 1px solid #dc3545; border-radius: 4px;">已拒绝</span>
                    @endif
                </td>
                <td style="padding: 12px;">{{ $afterSale->created_at }}</td>
                <td style="padding: 12px;">
                    <a href="{{ route('after-sales.show', $afterSale) }}" style="color: #007bff;">查看详情</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; text-align: center;">
        {{ $afterSales->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 60px 20px; color: #999;">
        <p>暂无售后服务记录</p>
        <a href="{{ route('orders.index') }}" style="color: #e4393c; margin-top: 10px; display: inline-block;">查看我的订单</a>
    </div>
    @endif
</div>
@endsection
