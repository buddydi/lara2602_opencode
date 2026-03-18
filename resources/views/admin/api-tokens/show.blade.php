@extends('admin_layout')

@section('title', 'Token详情')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Token详情</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.api-tokens.edit', $apiToken) }}" class="btn btn-primary btn-sm">编辑</a>
                        <a href="{{ route('admin.api-tokens.index') }}" class="btn btn-default btn-sm">返回列表</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 150px;">ID</th>
                            <td>{{ $apiToken->id }}</td>
                        </tr>
                        <tr>
                            <th>名称</th>
                            <td>{{ $apiToken->name }}</td>
                        </tr>
                        <tr>
                            <th>Token</th>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="token-value" value="{{ $apiToken->token }}" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyToken()">复制</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Guard</th>
                            <td><span class="badge badge-info">{{ $apiToken->guard }}</span></td>
                        </tr>
                        <tr>
                            <th>关联用户</th>
                            <td>{{ $apiToken->user?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>权限</th>
                            <td>
                                @if($apiToken->abilities)
                                    @foreach($apiToken->abilities as $ability)
                                        <span class="badge badge-secondary">{{ $ability }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>过期时间</th>
                            <td>
                                @if($apiToken->expires_at)
                                    {{ $apiToken->expires_at->format('Y-m-d H:i') }}
                                    @if($apiToken->isExpired())
                                        <span class="badge badge-danger">已过期</span>
                                    @endif
                                @else
                                    永不过期
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>最后使用</th>
                            <td>{{ $apiToken->last_used_at?->format('Y-m-d H:i:s') ?? '从未使用' }}</td>
                        </tr>
                        <tr>
                            <th>状态</th>
                            <td>
                                @if($apiToken->is_active)
                                    <span class="badge badge-success">启用</span>
                                @else
                                    <span class="badge badge-secondary">禁用</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>描述</th>
                            <td>{{ $apiToken->description ?? '无' }}</td>
                        </tr>
                        <tr>
                            <th>创建时间</th>
                            <td>{{ $apiToken->created_at }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <form action="{{ route('admin.api-tokens.toggle', $apiToken) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-{{ $apiToken->is_active ? 'warning' : 'success' }}">
                                {{ $apiToken->is_active ? '禁用' : '启用' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.api-tokens.regenerate', $apiToken) }}" method="POST" style="display:inline;" onsubmit="return confirm('确定重新生成Token？这将导致旧Token失效！')">
                            @csrf
                            <button type="submit" class="btn btn-danger">重新生成</button>
                        </form>
                        <form action="{{ route('admin.api-tokens.destroy', $apiToken) }}" method="POST" style="display:inline;" onsubmit="return confirm('确定删除？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary">删除</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">在线测试</h3>
                </div>
                <div class="card-body">
                    <form id="api-test-form">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="method" id="test-method" class="form-control" required>
                                    <option value="GET">GET</option>
                                    <option value="POST">POST</option>
                                    <option value="PUT">PUT</option>
                                    <option value="PATCH">PATCH</option>
                                    <option value="DELETE">DELETE</option>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <input type="url" name="url" id="test-url" class="form-control" placeholder="http://test.lara2602.local/api/products" required>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Authorization Token</label>
                            <input type="text" name="token" id="test-token" class="form-control" value="{{ $apiToken->token }}">
                        </div>

                        <div class="form-group">
                            <label>请求体 (JSON)</label>
                            <textarea name="body" id="test-body" class="form-control" rows="4" placeholder='{"key": "value"}'></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">发送请求</button>
                    </form>

                    <div class="mt-3">
                        <label>响应结果</label>
                        <pre id="test-response" style="background:#f5f5f5;padding:15px;border-radius:5px;min-height:200px;max-height:400px;overflow:auto;">等待发送请求...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function copyToken() {
    const input = document.getElementById('token-value');
    input.select();
    document.execCommand('copy');
    alert('Token已复制到剪贴板');
}

$(document).ready(function() {
    $('#api-test-form').on('submit', function(e) {
        e.preventDefault();
        
        const method = $('#test-method').val();
        const url = $('#test-url').val();
        const token = $('#test-token').val();
        const body = $('#test-body').val();
        
        $('#test-response').text('正在发送请求...');
        
        $.ajax({
            url: '{{ route('admin.api-tokens.test') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                method: method,
                url: url,
                token: token,
                body: body
            },
            success: function(response) {
                $('#test-response').text(JSON.stringify(response, null, 2));
            },
            error: function(xhr) {
                $('#test-response').text('请求失败: ' + xhr.status + '\n' + xhr.responseText);
            }
        });
    });
});
</script>
@endsection
