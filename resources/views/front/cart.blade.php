@extends('front.layout')

@section('title', '购物车')

@section('content')
@if($cartItems->count() > 0)
<form method="POST" action="{{ route('orders.store') }}">
    @csrf
    
    <table class="cart-table">
        <thead>
            <tr>
                <th style="width: 50px;"><input type="checkbox" id="select-all"></th>
                <th>商品信息</th>
                <th>规格</th>
                <th>单价</th>
                <th>数量</th>
                <th>小计</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartItems as $item)
            <tr>
                <td>
                    <input type="checkbox" name="cart_item_ids[]" value="{{ $item->id }}" class="cart-checkbox" checked>
                </td>
                <td>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <img src="{{ $item->product->cover_image ? asset('storage/' . $item->product->cover_image) : 'https://via.placeholder.com/60' }}" style="width: 60px; height: 60px; object-fit: cover;">
                        <a href="{{ route('products.detail', $item->product) }}" style="text-decoration:none;color:#333;">{{ $item->product->name }}</a>
                    </div>
                </td>
                <td>{{ $item->sku ? $item->sku->name : '-' }}</td>
                <td>¥{{ $item->sku ? $item->sku->price : $item->product->price }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:5px;">
                        <button type="button" onclick="updateQuantity({{ $item->id }}, -1)">-</button>
                        <input type="number" value="{{ $item->quantity }}" min="1" style="width:50px;text-align:center;" readonly>
                        <button type="button" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                    </div>
                </td>
                <td style="color:#e4393c;">¥{{ ($item->sku ? $item->sku->price : $item->product->price) * $item->quantity }}</td>
                <td>
                    <button type="button" onclick="deleteItem({{ $item->id }})" style="background:none;border:none;color:#e4393c;cursor:pointer;">删除</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="cart-summary">
        <div>已选 <span id="selected-count">0</span> 件商品</div>
        <div class="cart-total">合计: ¥<span id="selected-total">{{ $total }}</span></div>
        <button type="submit" class="btn" style="margin-top: 15px; width: 200px;">去结算</button>
    </div>
</form>
@else
<div style="text-align: center; padding: 80px 0;">
    <p style="color: #999; margin-bottom: 20px;">购物车是空的</p>
    <a href="{{ route('products.index') }}" class="btn">去购物</a>
</div>
@endif

<script>
function updateQuantity(itemId, delta) {
    fetch(`/cart/${itemId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ delta: delta })
    }).then(() => location.reload());
}

function deleteItem(itemId) {
    if (!confirm('确定要删除这件商品吗？')) return;
    fetch(`/cart/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => location.reload());
}

document.getElementById('select-all').addEventListener('change', function() {
    document.querySelectorAll('.cart-checkbox').forEach(cb => cb.checked = this.checked);
    updateSummary();
});

document.querySelectorAll('.cart-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSummary);
});

// 页面加载时初始化计算
updateSummary();

function updateSummary() {
    let count = 0;
    let total = 0;
    document.querySelectorAll('.cart-checkbox:checked').forEach(cb => {
        const row = cb.closest('tr');
        const price = parseFloat(row.cells[3].textContent.replace('¥', ''));
        const qty = parseInt(row.querySelector('input[type="number"]').value);
        count += qty;
        total += price * qty;
    });
    document.getElementById('selected-count').textContent = count;
    document.getElementById('selected-total').textContent = total.toFixed(2);
}
</script>
@endsection
