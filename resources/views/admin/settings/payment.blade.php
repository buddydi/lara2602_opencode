@extends('admin_layout')

@section('title', '支付方式')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">支付方式管理</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            添加支付方式
        </button>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>排序</th>
                    <th>名称</th>
                    <th>编码</th>
                    <th>描述</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->order }}</td>
                    <td>{{ $payment->name }}</td>
                    <td><code>{{ $payment->code }}</code></td>
                    <td>{{ $payment->description }}</td>
                    <td>
                        @if($payment->is_enabled)
                            <span class="badge bg-success">启用</span>
                        @else
                            <span class="badge bg-secondary">禁用</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $payment->id }}">
                            编辑
                        </button>
                        <form action="{{ route('admin.settings.payment.destroy', $payment) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('确定删除?')">删除</button>
                        </form>
                    </td>
                </tr>
                
                <div class="modal fade" id="editModal{{ $payment->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.settings.payment.update', $payment) }}" method="POST" class="modal-content">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">编辑支付方式</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">名称</label>
                                    <input type="text" name="name" value="{{ $payment->name }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">编码</label>
                                    <input type="text" name="code" value="{{ $payment->code }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">描述</label>
                                    <textarea name="description" class="form-control">{{ $payment->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">排序</label>
                                    <input type="number" name="order" value="{{ $payment->order }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_enabled" value="1" {{ $payment->is_enabled ? 'checked' : '' }} class="form-check-input" id="enable{{ $payment->id }}">
                                        <label class="form-check-label" for="enable{{ $payment->id }}">启用</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">保存</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr><td colspan="6" class="text-center">暂无数据</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.settings.payment.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">添加支付方式</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">名称</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">编码</label>
                    <input type="text" name="code" class="form-control" placeholder="如: alipay, wechat, bank" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">描述</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">排序</label>
                    <input type="number" name="order" value="0" class="form-control">
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_enabled" value="1" checked class="form-check-input" id="enableNew">
                        <label class="form-check-label" for="enableNew">启用</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">添加</button>
            </div>
        </form>
    </div>
</div>
@endsection
