@extends('admin_layout')

@section('title', '创建Token')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">创建API Token</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.api-tokens.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Token名称 <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="例如：测试Token" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Guard</label>
                            <select name="guard" class="form-control" required>
                                <option value="sanctum">Sanctum (后台用户)</option>
                                <option value="customer">Customer (前台客户)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>关联用户</label>
                            <select name="user_id" class="form-control">
                                <option value="">不关联</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>权限</label>
                    <div class="row">
                        @foreach($abilities as $key => $label)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="checkbox" name="abilities[]" value="{{ $key }}" class="form-check-input" id="ability_{{ $key }}">
                                <label class="form-check-label" for="ability_{{ $key }}">{{ $label }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>过期时间</label>
                            <input type="datetime-local" name="expires_at" class="form-control">
                            <small class="form-text text-muted">留空表示永不过期</small>
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

                <div class="form-group">
                    <label>描述</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">创建</button>
                <a href="{{ route('admin.api-tokens.index') }}" class="btn btn-default">取消</a>
            </form>
        </div>
    </div>
</div>
@endsection
