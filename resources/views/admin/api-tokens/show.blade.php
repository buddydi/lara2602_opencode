@extends('admin_layout')

@section('title', 'Token详情')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
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
                            <th style="width: 100px;">ID</th>
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
                                    <input type="text" class="form-control" id="token-value" value="{{ $apiToken->token }}" readonly style="font-size:12px;">
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
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">在线API测试</h3>
                </div>
                <div class="card-body">
                    <form id="api-test-form" onsubmit="return false;">
                        <div class="form-group">
                            <label>请求地址 (URL)</label>
                            <div class="row">
                                <div class="col-2">
                                    <select name="method" id="test-method" class="form-control" style="font-size:16px;padding:10px;">
                                        <option value="GET" selected>GET</option>
                                        <option value="POST">POST</option>
                                        <option value="PUT">PUT</option>
                                        <option value="PATCH">PATCH</option>
                                        <option value="DELETE">DELETE</option>
                                    </select>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="url" id="test-url" class="form-control" 
                                           value="http://test.lara2602.local/api/products" 
                                           placeholder="http://test.lara2602.local/api/products"
                                           style="font-size:14px;padding:10px;width:100%;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Authorization Token</label>
                            <input type="text" name="token" id="test-token" class="form-control" 
                                   value="{{ $apiToken->token }}"
                                   style="font-size:14px;padding:10px;width:100%;">
                        </div>

                        <div class="form-group">
                            <label>请求体 (JSON格式)</label>
                            <textarea name="body" id="test-body" class="form-control" rows="6" 
                                      placeholder='{"key": "value"}'
                                      style="font-family: Consolas, Monaco, monospace; font-size:14px;padding:12px;width:100%;"></textarea>
                        </div>

                        <button type="button" class="btn btn-primary btn-lg" id="send-btn" onclick="sendRequest()">发送请求</button>
                        <button type="button" class="btn btn-secondary btn-lg" onclick="clearResponse()">清空响应</button>
                    </form>

                    <div class="form-group mt-4">
                        <label><strong>响应结果</strong> <span id="response-status"></span></label>
                        <pre id="test-response" style="background:#1e1e1e;color:#d4d4d4;padding:15px;border-radius:5px;min-height:300px;max-height:500px;overflow:auto;font-size:13px;width:100%;">点击「发送请求」按钮开始测试...</pre>
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
    var input = document.getElementById('token-value');
    input.select();
    document.execCommand('copy');
    alert('Token已复制到剪贴板');
}

function clearResponse() {
    document.getElementById('test-response').textContent = '点击「发送请求」按钮开始测试...';
    document.getElementById('response-status').textContent = '';
}

function sendRequest() {
    var method = document.getElementById('test-method').value;
    var url = document.getElementById('test-url').value;
    var token = document.getElementById('test-token').value;
    var body = document.getElementById('test-body').value;
    var sendBtn = document.getElementById('send-btn');
    
    document.getElementById('test-response').textContent = '正在发送请求，请稍候...';
    document.getElementById('response-status').textContent = '';
    sendBtn.disabled = true;
    sendBtn.textContent = '请求中...';
    
    var formData = new FormData();
    formData.append('method', method);
    formData.append('url', url);
    formData.append('token', token);
    if (body.trim()) {
        formData.append('body', body);
    }
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route('admin.api-tokens.test') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function(response) {
        document.getElementById('response-status').innerHTML = '<span style="color: #4caf50;">HTTP ' + response.status + '</span>';
        return response.json();
    })
    .then(function(data) {
        document.getElementById('test-response').textContent = JSON.stringify(data, null, 2);
    })
    .catch(function(error) {
        document.getElementById('response-status').innerHTML = '<span style="color: #f44336;">请求失败</span>';
        document.getElementById('test-response').textContent = '错误: ' + error.message;
    })
    .finally(function() {
        sendBtn.disabled = false;
        sendBtn.textContent = '发送请求';
    });
}
</script>
@endsection
