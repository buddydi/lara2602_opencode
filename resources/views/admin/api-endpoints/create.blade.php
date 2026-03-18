@extends('admin_layout')

@section('title', '添加接口')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">添加API接口</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.api-endpoints.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>接口名称</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>请求方法</label>
                            <select name="method" class="form-control" required>
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                                <option value="PUT">PUT</option>
                                <option value="PATCH">PATCH</option>
                                <option value="DELETE">DELETE</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>接口路径</label>
                            <input type="text" name="path" class="form-control" placeholder="/api/products" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>分组</label>
                            <input type="text" name="group" class="form-control" list="groups" placeholder="商品接口/客户接口等" required>
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
                            <input type="number" name="sort" class="form-control" value="0">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>描述</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>参数说明 (JSON格式)</label>
                    <textarea name="parameters" class="form-control" rows="4" placeholder='[{"name": "page", "type": "integer", "required": false, "description": "页码"}]'></textarea>
                </div>

                <div class="form-group">
                    <label>响应示例</label>
                    <textarea name="response" class="form-control" rows="4" placeholder='{"success": true, "data": []}'></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="auth_required" value="1"> 需要认证
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" value="1" checked> 启用
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
