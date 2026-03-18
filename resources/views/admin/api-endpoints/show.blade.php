@extends('admin_layout')

@section('title', '接口详情')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">接口详情</h3>
            <div class="card-tools">
                <a href="{{ route('admin.api-endpoints.edit', $apiEndpoint) }}" class="btn btn-primary btn-sm">编辑</a>
                <a href="{{ route('admin.api-endpoints.index') }}" class="btn btn-default btn-sm">返回列表</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 150px;">ID</th>
                    <td>{{ $apiEndpoint->id }}</td>
                </tr>
                <tr>
                    <th>接口名称</th>
                    <td>{{ $apiEndpoint->name }}</td>
                </tr>
                <tr>
                    <th>请求方法</th>
                    <td><span class="badge badge-{{ $apiEndpoint->method_color }}">{{ $apiEndpoint->method }}</span></td>
                </tr>
                <tr>
                    <th>接口路径</th>
                    <td><code>{{ $apiEndpoint->path }}</code></td>
                </tr>
                <tr>
                    <th>分组</th>
                    <td>{{ $apiEndpoint->group }}</td>
                </tr>
                <tr>
                    <th>认证</th>
                    <td>
                        @if($apiEndpoint->auth_required)
                            <span class="badge badge-danger">需要认证</span>
                        @else
                            <span class="badge badge-secondary">无需认证</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>状态</th>
                    <td>
                        @if($apiEndpoint->is_active)
                            <span class="badge badge-success">启用</span>
                        @else
                            <span class="badge badge-secondary">禁用</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>描述</th>
                    <td>{{ $apiEndpoint->description ?? '无' }}</td>
                </tr>
                <tr>
                    <th>参数说明</th>
                    <td>
                        @if($apiEndpoint->parameters)
                            <pre>{{ json_encode($apiEndpoint->parameters, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @else
                            无
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>响应示例</th>
                    <td>
                        @if($apiEndpoint->response)
                            <pre>{{ json_encode($apiEndpoint->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @else
                            无
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>创建时间</th>
                    <td>{{ $apiEndpoint->created_at }}</td>
                </tr>
                <tr>
                    <th>更新时间</th>
                    <td>{{ $apiEndpoint->updated_at }}</td>
                </tr>
            </table>

            <div class="mt-3">
                <form action="{{ route('admin.api-endpoints.toggle', $apiEndpoint) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-{{ $apiEndpoint->is_active ? 'warning' : 'success' }}">
                        {{ $apiEndpoint->is_active ? '禁用' : '启用' }}
                    </button>
                </form>
                <form action="{{ route('admin.api-endpoints.destroy', $apiEndpoint) }}" method="POST" style="display:inline;" onsubmit="return confirm('确定删除？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">删除</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
