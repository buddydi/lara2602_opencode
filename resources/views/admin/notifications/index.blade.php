@extends('admin_layout')

@section('title', '消息管理')

@section('content')
<div class="page-header">
    <h1>消息管理</h1>
    <a href="{{ route('admin.notifications.create') }}" class="btn">发送消息</a>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <select name="type" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部类型</option>
            <option value="order" {{ request('type') === 'order' ? 'selected' : '' }}>订单通知</option>
            <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>退款通知</option>
            <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>系统通知</option>
        </select>
        <button type="submit" class="btn">筛选</button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>接收人</th>
            <th>类型</th>
            <th>标题</th>
            <th>内容</th>
            <th>状态</th>
            <th>发送时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notifications as $notification)
        <tr>
            <td>{{ $notification->customer->name }}</td>
            <td>
                @if($notification->type === 'order')
                <span class="badge">订单</span>
                @elseif($notification->type === 'refund')
                <span class="badge">退款</span>
                @else
                <span class="badge">系统</span>
                @endif
            </td>
            <td>{{ $notification->title }}</td>
            <td>{{ \Str::limit($notification->content, 50) }}</td>
            <td>
                @if($notification->status === 'unread')
                <span class="badge badge-warning">未读</span>
                @else
                <span class="badge badge-success">已读</span>
                @endif
            </td>
            <td>{{ $notification->created_at }}</td>
            <td>
                <form method="POST" action="{{ route('admin.notifications.destroy', $notification) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline" onclick="return confirm('确定删除？')">删除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $notifications->links() }}
</div>
@endsection
