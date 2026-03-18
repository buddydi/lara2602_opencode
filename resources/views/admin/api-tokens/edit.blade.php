@extends('admin_layout')

@section('title', '编辑Token')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">编辑API Token</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.api-tokens.update', $apiToken) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Token名称 <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $apiToken->name }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Guard</label>
                            <select name="guard" class="form-control" required>
                                <option value="sanctum" {{ $apiToken->guard == 'sanctum' ? 'selected' : '' }}>Sanctum (后台用户)</option>
                                <option value="customer" {{ $apiToken->guard == 'customer' ? 'selected' : '' }}>Customer (前台客户)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>关联用户</label>
                            <select name="user_id" class="form-control">
                                <option value="">不关联</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $apiToken->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
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
                                <input type="checkbox" name="abilities[]" value="{{ $key }}" class="form-check-input" id="ability_{{ $key }}"
                                    {{ in_array($key, $apiToken->abilities ?? []) ? 'checked' : '' }}>
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
                            <input type="datetime-local" name="expires_at" class="form-control" 
                                   value="{{ $apiToken->expires_at ? $apiToken->expires_at->format('Y-m-d\TH:i') : '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ $apiToken->is_active ? 'checked' : '' }}> 启用
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>描述</label>
                    <textarea name="description" class="form-control" rows="3">{{ $apiToken->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">保存</button>
                <a href="{{ route('admin.api-tokens.index') }}" class="btn btn-default">取消</a>
            </form>
        </div>
    </div>
</div>
@endsection
