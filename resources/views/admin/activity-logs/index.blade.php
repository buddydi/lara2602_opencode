@extends('admin_layout')

@section('title', '操作日志')

@section('content')
<div class="page-header">
    <h1>操作日志</h1>
    <form method="POST" action="{{ route('admin.activity-logs.clear') }}" style="display: inline;" onsubmit="return confirm('确定要清理日志吗？')">
        @csrf
        <button type="submit" class="btn btn-outline">清理30天前日志</button>
    </form>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <select name="module" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部模块</option>
            @foreach(App\Models\ActivityLog::getModuleOptions() as $key => $name)
            <option value="{{ $key }}" {{ request('module') === $key ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <select name="action" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">全部操作</option>
            @foreach(App\Models\ActivityLog::getActionOptions() as $key => $name)
            <option value="{{ $key }}" {{ request('action') === $key ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        <input type="date" name="start_date" value="{{ request('start_date') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        <span>至</span>
        <input type="date" name="end_date" value="{{ request('end_date') }}" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="搜索描述" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 150px;">
        <button type="submit" class="btn">筛选</button>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline">重置</a>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>时间</th>
            <th>操作人</th>
            <th>模块</th>
            <th>操作</th>
            <th>描述</th>
            <th>IP</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->id }}</td>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->user->name ?? '系统' }}</td>
            <td>
                <span class="badge">{{ App\Models\ActivityLog::getModuleOptions()[$log->module] ?? $log->module }}</span>
            </td>
            <td>
                @switch($log->action)
                    @case('create')
                    <span class="badge badge-success">{{ App\Models\ActivityLog::getActionOptions()[$log->action] ?? $log->action }}</span>
                    @break
                    @case('update')
                    <span class="badge badge-info">{{ App\Models\ActivityLog::getActionOptions()[$log->action] ?? $log->action }}</span>
                    @break
                    @case('delete')
                    <span class="badge badge-danger">{{ App\Models\ActivityLog::getActionOptions()[$log->action] ?? $log->action }}</span>
                    @break
                    @default
                    <span class="badge">{{ App\Models\ActivityLog::getActionOptions()[$log->action] ?? $log->action }}</span>
                @endswitch
            </td>
            <td>{{ \Str::limit($log->description, 50) }}</td>
            <td>{{ $log->ip_address }}</td>
            <td>
                <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm">详情</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $logs->links() }}
</div>
@endsection
