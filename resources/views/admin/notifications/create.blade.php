@extends('admin_layout')

@section('title', '发送消息')

@section('content')
<div class="page-header">
    <h1>发送消息</h1>
</div>

<form method="POST" action="{{ route('admin.notifications.store') }}">
    @csrf
    
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label>接收人 *</label>
                <select name="customer_id" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 300px;">
                    <option value="">选择用户</option>
                    <option value="all">全部用户</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>消息类型 *</label>
                <select name="type" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 200px;">
                    <option value="system">系统通知</option>
                    <option value="order">订单通知</option>
                    <option value="refund">退款通知</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>标题 *</label>
                <input type="text" name="title" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" placeholder="请输入消息标题">
            </div>
            
            <div class="form-group">
                <label>内容 *</label>
                <textarea name="content" rows="6" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" placeholder="请输入消息内容"></textarea>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <button type="submit" class="btn">发送消息</button>
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline" style="margin-left: 10px;">返回</a>
    </div>
</form>
@endsection
