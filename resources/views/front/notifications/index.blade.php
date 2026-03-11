@extends('front.layout')

@section('title', '我的消息')

@section('content')
<div style="background: #fff; padding: 30px; border-radius: 8px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>我的消息</h2>
        @if($unreadCount > 0)
        <a href="{{ route('notifications.markAllRead') }}" class="btn btn-outline" onclick="return confirm('确定全部标记为已读？')">全部标记为已读</a>
        @endif
    </div>
    
    <div style="margin-bottom: 20px;">
        <a href="{{ route('notifications.index') }}" style="margin-right: 15px; {{ !request('status') ? 'color:#e4393c;font-weight:bold;' : '' }}">全部</a>
        <a href="{{ route('notifications.index', ['status' => 'unread']) }}" style="margin-right: 15px; {{ request('status') == 'unread' ? 'color:#e4393c;font-weight:bold;' : '' }}">未读</a>
        <a href="{{ route('notifications.index', ['status' => 'read']) }}" style="{{ request('status') == 'read' ? 'color:#e4393c;font-weight:bold;' : '' }}">已读</a>
    </div>
    
    @if($notifications->isEmpty())
    <div style="text-align: center; padding: 50px; color: #999;">
        <p>暂无消息</p>
    </div>
    @else
    <div>
        @foreach($notifications as $notification)
        <a href="{{ route('notifications.show', $notification) }}" style="display: block; padding: 15px; border-bottom: 1px solid #eee; text-decoration: none; color: #333; {{ $notification->status == 'unread' ? 'background:#f8f9fa;' : '' }}">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1;">
                    @if($notification->status == 'unread')
                    <span style="display: inline-block; width: 8px; height: 8px; background: #e4393c; border-radius: 50%; margin-right: 8px;"></span>
                    @endif
                    <strong>{{ $notification->title }}</strong>
                </div>
                <span style="color: #999; font-size: 12px;">{{ $notification->created_at }}</span>
            </div>
            <div style="color: #666; font-size: 14px; margin-top: 5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{ $notification->content }}
            </div>
        </a>
        @endforeach
    </div>
    
    <div style="margin-top: 20px;">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
