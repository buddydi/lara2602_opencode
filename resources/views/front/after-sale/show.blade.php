@extends('front.layout')

@section('title', '售后详情')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <h2 style="margin-bottom: 20px;">售后详情</h2>
    
    <div style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
        <div style="display: flex; margin-bottom: 10px;">
            <span style="color: #999; width: 100px;">售后单号：</span>
            <span>{{ $afterSale->id }}</span>
        </div>
        <div style="display: flex; margin-bottom: 10px;">
            <span style="color: #999; width: 100px;">订单编号：</span>
            <span>{{ $afterSale->order->order_no }}</span>
        </div>
        <div style="display: flex; margin-bottom: 10px;">
            <span style="color: #999; width: 100px;">服务类型：</span>
            <span>
                @if($afterSale->type === 'return')
                <span style="color: #e4393c;">退货</span>
                @else
                <span style="color: #faa300;">换货</span>
                @endif
            </span>
        </div>
        <div style="display: flex; margin-bottom: 10px;">
            <span style="color: #999; width: 100px;">申请时间：</span>
            <span>{{ $afterSale->created_at }}</span>
        </div>
    </div>
    
    <h3 style="margin-bottom: 15px; font-size: 16px;">申请信息</h3>
    <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px;">
        <div style="margin-bottom: 10px;">
            <span style="color: #999;">申请原因：</span>
            <span>{{ $afterSale->reason }}</span>
        </div>
        @if($afterSale->description)
        <div>
            <span style="color: #999;">详细说明：</span>
            <span>{{ $afterSale->description }}</span>
        </div>
        @endif
    </div>
    
    <h3 style="margin-bottom: 15px; font-size: 16px;">处理状态</h3>
    <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px;">
        @if($afterSale->status === 'pending')
        <div style="color: #faa300; font-size: 16px;">等待商家处理...</div>
        @elseif($afterSale->status === 'processing')
        <div style="color: #007bff; font-size: 16px;">商家正在处理中</div>
        @elseif($afterSale->status === 'completed')
        <div style="color: #28a745; font-size: 16px;">售后已完成</div>
        @elseif($afterSale->status === 'rejected')
        <div style="color: #dc3545; font-size: 16px;">售后申请被拒绝</div>
        @endif
        
        @if($afterSale->admin_remark)
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
            <span style="color: #999;">商家备注：</span>
            <span>{{ $afterSale->admin_remark }}</span>
        </div>
        @endif
    </div>
    
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('after-sales.index') }}" class="btn btn-outline">返回列表</a>
        <a href="{{ route('orders.show', $afterSale->order) }}" class="btn btn-outline">查看订单</a>
    </div>
</div>
@endsection
