@extends('admin_layout')

@section('title', '日志详情')

@section('content')
<div class="page-header">
    <h1>日志详情</h1>
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline">返回列表</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h3>基本信息</h3>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="label">ID：</span>
                <span>{{ $activityLog->id }}</span>
            </div>
            <div class="info-row">
                <span class="label">操作人：</span>
                <span>{{ $activityLog->user->name ?? '系统' }}</span>
            </div>
            <div class="info-row">
                <span class="label">模块：</span>
                <span>{{ App\Models\ActivityLog::getModuleOptions()[$activityLog->module] ?? $activityLog->module }}</span>
            </div>
            <div class="info-row">
                <span class="label">操作：</span>
                <span>{{ App\Models\ActivityLog::getActionOptions()[$activityLog->action] ?? $activityLog->action }}</span>
            </div>
            <div class="info-row">
                <span class="label">描述：</span>
                <span>{{ $activityLog->description }}</span>
            </div>
            <div class="info-row">
                <span class="label">时间：</span>
                <span>{{ $activityLog->created_at }}</span>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>其他信息</h3>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="label">IP 地址：</span>
                <span>{{ $activityLog->ip_address }}</span>
            </div>
            <div class="info-row">
                <span class="label">User Agent：</span>
                <span style="word-break: break-all;">{{ $activityLog->user_agent }}</span>
            </div>
            @if($activityLog->target_type)
            <div class="info-row">
                <span class="label">关联模型：</span>
                <span>{{ $activityLog->target_type }} #{{ $activityLog->target_id }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

@if($activityLog->old_values || $activityLog->new_values)
<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3>数据变更</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            @if($activityLog->old_values)
            <div>
                <h4 style="margin-bottom: 10px; color: #dc3545;">修改前</h4>
                <pre style="background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto;">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            @endif
            @if($activityLog->new_values)
            <div>
                <h4 style="margin-bottom: 10px; color: #28a745;">修改后</h4>
                <pre style="background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto;">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<div style="margin-top: 20px;">
    <form method="POST" action="{{ route('admin.activity-logs.destroy', $activityLog) }}" onsubmit="return confirm('确定要删除此日志吗？')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline" style="color: #dc3545; border-color: #dc3545;">删除此日志</button>
    </form>
</div>
@endsection
