@extends('admin_layout')

@section('title', '配送方式')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">配送方式管理</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            添加配送方式
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
                    <th>首重/首费</th>
                    <th>续重/续费</th>
                    <th>包邮门槛</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shippings as $shipping)
                <tr>
                    <td>{{ $shipping->order }}</td>
                    <td>{{ $shipping->name }}</td>
                    <td><code>{{ $shipping->code }}</code></td>
                    <td>{{ $shipping->first_weight }}kg / ¥{{ $shipping->first_price }}</td>
                    <td>{{ $shipping->continue_weight }}kg / ¥{{ $shipping->continue_price }}</td>
                    <td>{{ $shipping->free_shipping_amount ? '¥' . $shipping->free_shipping_amount : '-' }}</td>
                    <td>
                        @if($shipping->is_enabled)
                            <span class="badge bg-success">启用</span>
                        @else
                            <span class="badge bg-secondary">禁用</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $shipping->id }}">
                            编辑
                        </button>
                        <form action="{{ route('admin.settings.shipping.destroy', $shipping) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('确定删除?')">删除</button>
                        </form>
                    </td>
                </tr>
                
                <div class="modal fade" id="editModal{{ $shipping->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.settings.shipping.update', $shipping) }}" method="POST" class="modal-content">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">编辑配送方式</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">名称</label>
                                    <input type="text" name="name" value="{{ $shipping->name }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">编码</label>
                                    <input type="text" name="code" value="{{ $shipping->code }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">描述</label>
                                    <textarea name="description" class="form-control">{{ $shipping->description }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">首重(kg)</label>
                                        <input type="number" step="0.01" name="first_weight" value="{{ $shipping->first_weight }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">首费(元)</label>
                                        <input type="number" step="0.01" name="first_price" value="{{ $shipping->first_price }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">续重(kg)</label>
                                        <input type="number" step="0.01" name="continue_weight" value="{{ $shipping->continue_weight }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">续费(元)</label>
                                        <input type="number" step="0.01" name="continue_price" value="{{ $shipping->continue_price }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">包邮门槛(元)</label>
                                        <input type="number" step="0.01" name="free_shipping_amount" value="{{ $shipping->free_shipping_amount }}" class="form-control" placeholder="0或不填">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">预计天数</label>
                                        <input type="number" name="estimated_days" value="{{ $shipping->estimated_days }}" class="form-control" placeholder="如: 3">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">排序</label>
                                    <input type="number" name="order" value="{{ $shipping->order }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_enabled" value="1" {{ $shipping->is_enabled ? 'checked' : '' }} class="form-check-input" id="enable{{ $shipping->id }}">
                                        <label class="form-check-label" for="enable{{ $shipping->id }}">启用</label>
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
                <tr><td colspan="8" class="text-center">暂无数据</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.settings.shipping.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">添加配送方式</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">名称</label>
                    <input type="text" name="name" class="form-control" placeholder="如: 快递配送" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">编码</label>
                    <input type="text" name="code" class="form-control" placeholder="如: express, pickup" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">描述</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">首重(kg)</label>
                        <input type="number" step="0.01" name="first_weight" value="1" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">首费(元)</label>
                        <input type="number" step="0.01" name="first_price" value="10" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">续重(kg)</label>
                        <input type="number" step="0.01" name="continue_weight" value="1" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">续费(元)</label>
                        <input type="number" step="0.01" name="continue_price" value="5" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">包邮门槛(元)</label>
                        <input type="number" step="0.01" name="free_shipping_amount" class="form-control" placeholder="0或不填">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">预计天数</label>
                        <input type="number" name="estimated_days" class="form-control" placeholder="如: 3">
                    </div>
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
