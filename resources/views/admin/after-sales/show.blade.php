@extends('admin_layout')

@section('title', '售后详情')

@section('content')
<div class="page-header">
    <h1>售后详情</h1>
    <a href="{{ route('admin.after-sales.index') }}" class="btn btn-outline">返回列表</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h3>售后信息</h3>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="label">类型：</span>
                <span>{{ $afterSale->type === 'return' ? '退货' : '换货' }}</span>
            </div>
            <div class="info-row">
                <span class="label">原因：</span>
                <span>{{ $afterSale->reason }}</span>
            </div>
            <div class="info-row">
                <span class="label">详细说明：</span>
                <span>{{ $afterSale->description ?: '无' }}</span>
            </div>
            <div class="info-row">
                <span class="label">申请时间：</span>
                <span>{{ $afterSale->created_at }}</span>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>订单信息</h3>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="label">订单号：</span>
                <span><a href="{{ route('admin.orders.show', $afterSale->order) }}">{{ $afterSale->order->order_no }}</a></span>
            </div>
            <div class="info-row">
                <span class="label">客户：</span>
                <span>{{ $afterSale->customer->name }}</span>
            </div>
            <div class="info-row">
                <span class="label">订单金额：</span>
                <span>¥{{ $afterSale->order->pay_amount }}</span>
            </div>
            <div class="info-row">
                <span class="label">订单状态：</span>
                <span>{{ $afterSale->order->status_text }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3>处理售后</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.after-sales.update', $afterSale) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>处理状态</label>
                <select name="status" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 200px;">
                    <option value="pending" {{ $afterSale->status === 'pending' ? 'selected' : '' }}>待处理</option>
                    <option value="processing" {{ $afterSale->status === 'processing' ? 'selected' : '' }}>处理中</option>
                    <option value="completed" {{ $afterSale->status === 'completed' ? 'selected' : '' }}>已完成</option>
                    <option value="rejected" {{ $afterSale->status === 'rejected' ? 'selected' : '' }}>已拒绝</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>管理员备注</label>
                <textarea name="admin_remark" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">{{ $afterSale->admin_remark }}</textarea>
            </div>
            
            <button type="submit" class="btn">保存</button>
        </form>
    </div>
</div>
@endsection
