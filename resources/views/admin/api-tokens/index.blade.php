@extends('admin_layout')

@section('title', 'API Token管理')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">API Token管理</h3>
            <div class="card-tools">
                <a href="{{ route('admin.api-tokens.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> 创建Token
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <select name="guard" class="form-control">
                            <option value="">全部Guard</option>
                            <option value="sanctum" {{ request('guard') == 'sanctum' ? 'selected' : '' }}>Sanctum</option>
                            <option value="customer" {{ request('guard') == 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="is_active" class="form-control">
                            <option value="">全部状态</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>启用</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>禁用</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-secondary">筛选</button>
                        <a href="{{ route('admin.api-tokens.index') }}" class="btn btn-default">重置</a>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>Token</th>
                        <th>Guard</th>
                        <th>关联用户</th>
                        <th>过期时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokens as $token)
                    <tr>
                        <td>{{ $token->id }}</td>
                        <td>{{ $token->name }}</td>
                        <td><code>{{ Str::limit(substr($token->getAttributes()['token'] ?? '', 0, 30), 30) }}</code></td>
                        <td><span class="badge badge-info">{{ $token->guard }}</span></td>
                        <td>{{ $token->user?->name ?? '-' }}</td>
                        <td>
                            @if($token->expires_at)
                                {{ $token->expires_at->format('Y-m-d H:i') }}
                                @if($token->isExpired())
                                    <span class="badge badge-danger">已过期</span>
                                @endif
                            @else
                                永不过期
                            @endif
                        </td>
                        <td>
                            @if($token->is_active)
                                <span class="badge badge-success">启用</span>
                            @else
                                <span class="badge badge-secondary">禁用</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.api-tokens.show', $token) }}" class="btn btn-info btn-sm">详情</a>
                            <form action="{{ route('admin.api-tokens.toggle', $token) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-{{ $token->is_active ? 'warning' : 'success' }} btn-sm">
                                    {{ $token->is_active ? '禁用' : '启用' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.api-tokens.destroy', $token) }}" method="POST" style="display:inline;" onsubmit="return confirm('确定删除？')">
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
            {{ $tokens->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
