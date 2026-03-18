@extends('admin_layout')

@section('title', '编辑接口')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">编辑API接口</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.api-endpoints.update', $apiEndpoint) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>接口名称</label>
                    <input type="text" name="name" class="form-control" value="{{ $apiEndpoint->name }}" required>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>请求方法</label>
                            <select name="method" class="form-control" required>
                                <option value="GET" {{ $apiEndpoint->method == 'GET' ? 'selected' : '' }}>GET</option>
                                <option value="POST" {{ $apiEndpoint->method == 'POST' ? 'selected' : '' }}>POST</option>
                                <option value="PUT" {{ $apiEndpoint->method == 'PUT' ? 'selected' : '' }}>PUT</option>
                                <option value="PATCH" {{ $apiEndpoint->method == 'PATCH' ? 'selected' : '' }}>PATCH</option>
                                <option value="DELETE" {{ $apiEndpoint->method == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>接口路径</label>
                            <input type="text" name="path" class="form-control" value="{{ $apiEndpoint->path }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>分组</label>
                            <input type="text" name="group" class="form-control" list="groups" value="{{ $apiEndpoint->group }}" required>
                            <datalist id="groups">
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>排序</label>
                            <input type="number" name="sort" class="form-control" value="{{ $apiEndpoint->sort }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>描述</label>
                    <textarea name="description" class="form-control" rows="3">{{ $apiEndpoint->description }}</textarea>
                </div>

                <div class="form-group">
                    <label>参数说明 (JSON格式)</label>
                    <textarea name="parameters" class="form-control" rows="4">{{ is_array($apiEndpoint->parameters) ? json_encode($apiEndpoint->parameters, JSON_UNESCAPED_UNICODE) : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>响应示例</label>
                    <textarea name="response" class="form-control" rows="4">{{ $apiEndpoint->response }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="auth_required" value="1" {{ $apiEndpoint->auth_required ? 'checked' : '' }}> 需要认证
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ $apiEndpoint->is_active ? 'checked' : '' }}> 启用
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">保存</button>
                <a href="{{ route('admin.api-endpoints.index') }}" class="btn btn-default">取消</a>
            </form>
        </div>
    </div>
</div>
@endsection
