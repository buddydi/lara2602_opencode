@extends('admin_layout')

@section('title', 'API接口管理')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">API接口管理</h3>
            <div class="card-tools">
                <a href="{{ route('admin.api-endpoints.sync') }}" class="btn btn-success btn-sm" onclick="return confirm('确定要同步所有路由吗？')">
                    <i class="fas fa-sync"></i> 同步路由
                </a>
                <a href="{{ route('admin.api-endpoints.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> 添加接口
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-2">
                        <select name="group" class="form-control">
                            <option value="">全部分组</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="method" class="form-control">
                            <option value="">全部方法</option>
                            <option value="GET" {{ request('method') == 'GET' ? 'selected' : '' }}>GET</option>
                            <option value="POST" {{ request('method') == 'POST' ? 'selected' : '' }}>POST</option>
                            <option value="PUT" {{ request('method') == 'PUT' ? 'selected' : '' }}>PUT</option>
                            <option value="PATCH" {{ request('method') == 'PATCH' ? 'selected' : '' }}>PATCH</option>
                            <option value="DELETE" {{ request('method') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="搜索接口名称或路径" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary">筛选</button>
                        <a href="{{ route('admin.api-endpoints.index') }}" class="btn btn-default">重置</a>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>接口名称</th>
                        <th>方法</th>
                        <th>路径</th>
                        <th>分组</th>
                        <th>认证</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($endpoints as $endpoint)
                    <tr>
                        <td>{{ $endpoint->id }}</td>
                        <td>{{ $endpoint->name }}</td>
                        <td>
                            <span class="badge badge-{{ $endpoint->method_color }}">{{ $endpoint->method }}</span>
                        </td>
                        <td><code>{{ $endpoint->path }}</code></td>
                        <td>{{ $endpoint->group }}</td>
                        <td>
                            @if($endpoint->auth_required)
                                <span class="badge badge-danger">需要</span>
                            @else
                                <span class="badge badge-secondary">不需要</span>
                            @endif
                        </td>
                        <td>
                            @if($endpoint->is_active)
                                <span class="badge badge-success">启用</span>
                            @else
                                <span class="badge badge-secondary">禁用</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.api-endpoints.show', $endpoint) }}" class="btn btn-info btn-sm">详情</a>
                            <a href="{{ route('admin.api-endpoints.edit', $endpoint) }}" class="btn btn-primary btn-sm">编辑</a>
                            <form action="{{ route('admin.api-endpoints.toggle', $endpoint) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-{{ $endpoint->is_active ? 'warning' : 'success' }} btn-sm">
                                    {{ $endpoint->is_active ? '禁用' : '启用' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.api-endpoints.destroy', $endpoint) }}" method="POST" style="display:inline;" onsubmit="return confirm('确定删除？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">删除</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">暂无数据</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $endpoints->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
