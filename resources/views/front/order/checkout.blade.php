<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>结算页面 - NewShop</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1 { font-size: 24px; margin-bottom: 20px; }
        .section { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        
        .address-list { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .address-item { border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; transition: all 0.2s; position: relative; }
        .address-item:hover { border-color: #e4393c; }
        .address-item.selected { border-color: #e4393c; background: #fff5f5; }
        .address-item.default::before { content: '默认'; position: absolute; top: 10px; right: 10px; background: #e4393c; color: #fff; font-size: 12px; padding: 2px 8px; border-radius: 4px; }
        .address-name { font-weight: bold; margin-bottom: 5px; }
        .address-phone { color: #666; font-size: 14px; }
        .address-detail { color: #999; font-size: 14px; margin-top: 5px; }
        
        .shipping-methods { display: flex; gap: 20px; }
        .shipping-method { flex: 1; border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; text-align: center; transition: all 0.2s; }
        .shipping-method:hover { border-color: #e4393c; }
        .shipping-method.selected { border-color: #e4393c; background: #fff5f5; }
        .shipping-method-name { font-weight: bold; }
        .shipping-method-price { color: #e4393c; margin-top: 5px; }
        
        .cart-items { border: 1px solid #eee; border-radius: 8px; overflow: hidden; }
        .cart-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #eee; }
        .cart-item:last-child { border-bottom: none; }
        .cart-item-img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; background: #f5f5f5; }
        .cart-item-info { flex: 1; margin-left: 15px; }
        .cart-item-name { font-weight: bold; margin-bottom: 5px; }
        .cart-item-sku { color: #999; font-size: 14px; }
        .cart-item-price { color: #e4393c; font-size: 16px; font-weight: bold; }
        .cart-item-qty { color: #666; margin-left: 20px; }
        
        .summary { background: #fff; border-radius: 8px; padding: 20px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .summary-total { font-size: 18px; font-weight: bold; border-top: 1px solid #eee; padding-top: 15px; margin-top: 15px; }
        .summary-total .price { color: #e4393c; font-size: 24px; }
        
        .btn { display: inline-block; padding: 12px 30px; background: #e4393c; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; text-align: center; text-decoration: none; }
        .btn:hover { background: #d3303a; }
        .btn:disabled { background: #ccc; cursor: not-allowed; }
        .btn-sm { padding: 6px 12px; font-size: 14px; }
        .btn-outline { background: #fff; border: 1px solid #ddd; color: #333; }
        .btn-outline:hover { background: #f5f5f5; }
        
        .no-address { text-align: center; padding: 40px; color: #999; }
        
        /* 侧边栏弹窗 */
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 999; }
        .sidebar-overlay.show { display: block; }
        .sidebar { position: fixed; top: 0; right: -400px; width: 400px; height: 100%; background: #fff; z-index: 1000; transition: right 0.3s; overflow-y: auto; }
        .sidebar.show { right: 0; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .sidebar-header h3 { font-size: 18px; }
        .sidebar-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #999; }
        .sidebar-body { padding: 20px; }
        .sidebar-footer { padding: 20px; border-top: 1px solid #eee; }
        
        .address-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 15px; position: relative; }
        .address-card.selected { border-color: #e4393c; background: #fff5f5; }
        .address-card .default-tag { position: absolute; top: 10px; right: 10px; background: #e4393c; color: #fff; font-size: 12px; padding: 2px 8px; border-radius: 4px; }
        .address-card .actions { margin-top: 10px; display: flex; gap: 10px; }
        .address-card .actions a, .address-card .actions button { color: #666; font-size: 14px; text-decoration: none; background: none; border: none; cursor: pointer; }
        .address-card .actions a:hover, .address-card .actions button:hover { color: #e4393c; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #333; font-size: 14px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #e4393c; }
        
        .alert { padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .alert-error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <a href="{{ route('cart.index') }}" style="color:#333;text-decoration:none;">&lt; 返回购物车</a>
            确认订单
        </h1>
        
        <form id="checkout-form" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <input type="hidden" name="address_id" id="address_id" value="">
            <input type="hidden" name="shipping_method" id="shipping_method" value="standard">
            <input type="hidden" name="payment_method" id="payment_method" value="online">
            @foreach($cartItems as $item)
            <input type="hidden" name="cart_item_ids[]" value="{{ $item->id }}">
            @endforeach
            
            <div class="section">
                <div class="section-title">
                    收货地址
                    <a href="javascript:void(0)" onclick="openAddressManage()" style="float:right;font-size:14px;font-weight:normal;color:#e4393c;">管理地址</a>
                </div>
                <div id="address-list-container">
                    @if($addresses->count() > 0)
                    <div class="address-list">
                        @foreach($addresses as $address)
                        <div class="address-item {{ $address->is_default ? 'default' : '' }}" data-id="{{ $address->id }}" onclick="selectAddress({{ $address->id }})">
                            <div class="address-name">{{ $address->name }}</div>
                            <div class="address-phone">{{ $address->phone }}</div>
                            <div class="address-detail">{{ $address->province }} {{ $address->city }} {{ $address->district }} {{ $address->detail_address }}</div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="no-address">
                        暂无收货地址，<a href="javascript:void(0)" onclick="openAddressManage()">点击添加</a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">配送方式</div>
                <div class="shipping-methods">
                    <div class="shipping-method selected" data-method="standard" onclick="selectShipping('standard', 0)">
                        <div class="shipping-method-name">标准配送</div>
                        <div class="shipping-method-price">免费</div>
                    </div>
                    <div class="shipping-method" data-method="express" onclick="selectShipping('express', 10)">
                        <div class="shipping-method-name">快递配送</div>
                        <div class="shipping-method-price">¥10.00</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">商品清单</div>
                <div class="cart-items">
                    @foreach($cartItems as $item)
                    <div class="cart-item">
                        <img src="{{ $item->product->cover_image ? asset('storage/' . $item->product->cover_image) : 'https://via.placeholder.com/80' }}" class="cart-item-img" alt="{{ $item->product->name }}">
                        <div class="cart-item-info">
                            <div class="cart-item-name">{{ $item->product->name }}</div>
                            @if($item->sku)
                            <div class="cart-item-sku">{{ $item->sku->name }}</div>
                            @endif
                        </div>
                        <div class="cart-item-price">¥{{ ($item->sku ? $item->sku->price : $item->product->price) }}</div>
                        <div class="cart-item-qty">x {{ $item->quantity }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="summary">
                <div class="summary-row">
                    <span>商品总价</span>
                    <span>¥{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>运费</span>
                    <span id="shipping-fee">¥0.00</span>
                </div>
                @if(isset($coupons) && $coupons->count() > 0)
                <div class="summary-row" style="color: #faa300;">
                    <span>可用优惠券</span>
                    <span>{{ $coupons->count() }} 张</span>
                </div>
                <div class="summary-row">
                    <span>优惠券码</span>
                    <span>
                        <input type="text" id="coupon-code" name="coupon_code" 
                            placeholder="输入优惠码" 
                            style="width: 120px; padding: 5px; text-align: center;">
                        <button type="button" onclick="applyCoupon()" style="padding: 5px 10px; background: #faa300; color: #fff; border: none; border-radius: 4px; cursor: pointer;">使用</button>
                    </span>
                </div>
                <div class="summary-row" id="coupon-discount-row" style="display: none;">
                    <span>优惠券优惠</span>
                    <span id="coupon-discount" style="color: #faa300;">-¥0.00</span>
                </div>
                @endif
                @if($points > 0)
                <div class="summary-row" style="color: #28a745;">
                    <span>可用积分</span>
                    <span>{{ $points }} 积分</span>
                </div>
                <div class="summary-row">
                    <span>使用积分</span>
                    <span>
                        <input type="number" id="points-input" name="points_used" 
                            min="0" max="{{ min($points, $maxDeduction) }}" 
                            value="0" 
                            style="width: 80px; padding: 5px; text-align: center;"
                            onchange="calculateTotal()">
                        <span style="color: #999; font-size: 12px;">({{ $deductionRate }}积分抵扣¥1)</span>
                    </span>
                </div>
                <div class="summary-row" id="points-deduction-row" style="display: none;">
                    <span>积分抵扣</span>
                    <span id="points-deduction" style="color: #28a745;">-¥0.00</span>
                </div>
                @endif
                <div class="summary-row summary-total">
                    <span>应付总额</span>
                    <span class="price" id="total-price">¥{{ number_format($subtotal, 2) }}</span>
                </div>
                <button type="submit" class="btn" id="submit-btn">提交订单</button>
            </div>
        </form>
    </div>
    
    <!-- 侧边栏弹窗 -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3 id="sidebar-title">管理收货地址</h3>
            <button class="sidebar-close" onclick="closeSidebar()">&times;</button>
        </div>
        <div class="sidebar-body" id="sidebar-body">
            <!-- 动态内容 -->
        </div>
        <div class="sidebar-footer" id="sidebar-footer">
            <button class="btn btn-block" onclick="showAddressForm()">新增地址</button>
        </div>
    </div>
    
    <script>
        let selectedAddressId = {{ $addresses->where('is_default', true)->first()?->id ?? $addresses->first()?->id ?? 0 }};
        let shippingFee = 0;
        let subtotal = {{ $subtotal }};
        let deductionRate = {{ $deductionRate }};
        
        function calculateTotal() {
            let pointsInput = document.getElementById('points-input');
            let pointsDeductionRow = document.getElementById('points-deduction-row');
            let pointsDeductionEl = document.getElementById('points-deduction');
            let totalPriceEl = document.getElementById('total-price');
            
            let pointsUsed = parseInt(pointsInput?.value || 0);
            if (isNaN(pointsUsed) || pointsUsed < 0) pointsUsed = 0;
            
            let maxPoints = Math.min({{ $points ?? 0 }}, {{ $maxDeduction }});
            if (pointsUsed > maxPoints) {
                pointsUsed = maxPoints;
                pointsInput.value = pointsUsed;
            }
            
            let deduction = pointsUsed / deductionRate;
            let total = subtotal + shippingFee - deduction;
            if (total < 0.01) total = 0.01;
            
            if (pointsUsed > 0) {
                pointsDeductionRow.style.display = 'flex';
                pointsDeductionEl.textContent = '-¥' + deduction.toFixed(2);
            } else {
                pointsDeductionRow.style.display = 'none';
            }
            
            totalPriceEl.textContent = '¥' + total.toFixed(2);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            if (selectedAddressId > 0) {
                document.getElementById('address_id').value = selectedAddressId;
                document.querySelectorAll('.address-item').forEach(item => {
                    item.classList.toggle('selected', item.dataset.id == selectedAddressId);
                });
            }
            calculateTotal();
        });
        
        function selectAddress(id) {
            selectedAddressId = id;
            document.getElementById('address_id').value = id;
            document.querySelectorAll('.address-item').forEach(item => {
                item.classList.toggle('selected', item.dataset.id == id);
            });
        }
        
        function selectShipping(method, fee) {
            shippingFee = fee;
            document.getElementById('shipping_method').value = method;
            document.getElementById('shipping-fee').textContent = '¥' + fee.toFixed(2);
            document.getElementById('total-price').textContent = '¥' + ({{ $subtotal }} + fee).toFixed(2);
            document.querySelectorAll('.shipping-method').forEach(item => {
                item.classList.toggle('selected', item.dataset.method == method);
            });
        }
        
        // 侧边栏
        function openAddressManage() {
            document.getElementById('sidebar-overlay').classList.add('show');
            document.getElementById('sidebar').classList.add('show');
            loadAddressList();
        }
        
        function closeSidebar() {
            document.getElementById('sidebar-overlay').classList.remove('show');
            document.getElementById('sidebar').classList.remove('show');
        }
        
        function loadAddressList() {
            document.getElementById('sidebar-title').textContent = '管理收货地址';
            document.getElementById('sidebar-footer').style.display = 'block';
            
            fetch('{{ route("addresses.index") }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.addresses && data.addresses.length > 0) {
                    data.addresses.forEach(addr => {
                        html += `
                            <div class="address-card ${addr.is_default ? 'selected' : ''}" data-id="${addr.id}">
                                ${addr.is_default ? '<span class="default-tag">默认</span>' : ''}
                                <div style="font-weight:bold;">${addr.name} ${addr.phone}</div>
                                <div style="color:#666;font-size:14px;margin-top:5px;">${addr.province}${addr.city}${addr.district}${addr.detail_address}</div>
                                <div class="actions">
                                    <a href="javascript:void(0)" onclick="selectAndClose(${addr.id})">选中</a>
                                    <a href="javascript:void(0)" onclick="editAddress(${addr.id})">编辑</a>
                                    ${!addr.is_default ? `<button type="button" onclick="setDefault(${addr.id})">设为默认</button>` : ''}
                                    <button type="button" onclick="deleteAddress(${addr.id})" style="color:#e4393c;">删除</button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="no-address">暂无收货地址</div>';
                }
                document.getElementById('sidebar-body').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('sidebar-body').innerHTML = '<div class="alert alert-error">加载失败</div>';
            });
        }
        
        function selectAndClose(id) {
            selectAddress(id);
            closeSidebar();
        }
        
        function showAddressForm(addressId = null) {
            const isEdit = addressId !== null;
            document.getElementById('sidebar-title').textContent = isEdit ? '编辑地址' : '新增地址';
            document.getElementById('sidebar-footer').style.display = 'none';
            
            let html = '<div id="address-form-msg"></div>';
            html += '<form id="address-form" onsubmit="saveAddress(event)">';
            html += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            if (isEdit) {
                html += '<input type="hidden" name="_method" value="PUT">';
                html += `<input type="hidden" name="address_id" value="${addressId}">`;
            }
            html += `
                <div class="form-group">
                    <label>收货人姓名 *</label>
                    <input type="text" name="name" id="form-name" required>
                </div>
                <div class="form-group">
                    <label>联系电话 *</label>
                    <input type="text" name="phone" id="form-phone" required>
                </div>
                <div class="form-group">
                    <label>省份 *</label>
                    <input type="text" name="province" id="form-province" required>
                </div>
                <div class="form-group">
                    <label>城市 *</label>
                    <input type="text" name="city" id="form-city" required>
                </div>
                <div class="form-group">
                    <label>区/县 *</label>
                    <input type="text" name="district" id="form-district" required>
                </div>
                <div class="form-group">
                    <label>详细地址 *</label>
                    <textarea name="detail_address" id="form-detail_address" required></textarea>
                </div>
                <div class="form-group">
                    <label>邮政编码</label>
                    <input type="text" name="postal_code" id="form-postal_code">
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_default" id="form-is_default" value="1"> 设为默认地址
                    </label>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn" style="flex:1;">保存</button>
                    <button type="button" class="btn btn-outline" style="flex:1;" onclick="loadAddressList()">取消</button>
                </div>
            `;
            html += '</form>';
            
            document.getElementById('sidebar-body').innerHTML = html;
            
            if (isEdit) {
                fetch(`/addresses/${addressId}`, { headers: { 'Accept': 'application/json' }})
                .then(r => r.json())
                .then(addr => {
                    document.getElementById('form-name').value = addr.name || '';
                    document.getElementById('form-phone').value = addr.phone || '';
                    document.getElementById('form-province').value = addr.province || '';
                    document.getElementById('form-city').value = addr.city || '';
                    document.getElementById('form-district').value = addr.district || '';
                    document.getElementById('form-detail_address').value = addr.detail_address || '';
                    document.getElementById('form-postal_code').value = addr.postal_code || '';
                    document.getElementById('form-is_default').checked = addr.is_default;
                });
            }
        }
        
        function editAddress(id) {
            showAddressForm(id);
        }
        
        function saveAddress(e) {
            e.preventDefault();
            const form = document.getElementById('address-form');
            const addressIdInput = form.querySelector('input[name="address_id"]');
            const isEdit = addressIdInput && addressIdInput.value;
            const addressId = isEdit ? addressIdInput.value : null;
            const url = isEdit ? `/addresses/${addressId}` : '{{ route("addresses.store") }}';
            const method = isEdit ? 'PUT' : 'POST';
            
            // Get all form fields manually
            const name = document.getElementById('form-name').value;
            const phone = document.getElementById('form-phone').value;
            const province = document.getElementById('form-province').value;
            const city = document.getElementById('form-city').value;
            const district = document.getElementById('form-district').value;
            const detail_address = document.getElementById('form-detail_address').value;
            const postal_code = document.getElementById('form-postal_code').value;
            const is_default = document.getElementById('form-is_default').checked ? 1 : 0;
            
            console.log('Form values:', { name, phone, province, city, district, detail_address, postal_code, is_default });
            
            const params = new URLSearchParams();
            params.append('_token', '{{ csrf_token() }}');
            params.append('name', name);
            params.append('phone', phone);
            params.append('province', province);
            params.append('city', city);
            params.append('district', district);
            params.append('detail_address', detail_address);
            if (postal_code) params.append('postal_code', postal_code);
            if (is_default) params.append('is_default', is_default);
            if (isEdit) params.append('_method', 'PUT');
            
            console.log('Params:', params.toString());
            
            fetch(url, {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json'
                },
                body: params
            })
            .then(r => r.json())
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    loadAddressList();
                    refreshAddressList();
                } else if (data.errors) {
                    let msg = '';
                    for (let field in data.errors) {
                        msg += data.errors[field].join('<br>') + '<br>';
                    }
                    document.getElementById('address-form-msg').innerHTML = '<div class="alert alert-error">' + msg + '</div>';
                } else {
                    document.getElementById('address-form-msg').innerHTML = '<div class="alert alert-error">' + (data.message || '保存失败') + '</div>';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('address-form-msg').innerHTML = '<div class="alert alert-error">保存失败</div>';
            });
        }
        
        function setDefault(id) {
            if (!confirm('确定要设为默认地址吗？')) return;
            const params = new URLSearchParams();
            params.append('_token', '{{ csrf_token() }}');
            
            fetch(`/addresses/${id}/set-default`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: params
            })
            .then(r => r.json())
            .then(data => {
                console.log('SetDefault response:', data);
                if (data.success) {
                    loadAddressList();
                    refreshAddressList();
                } else {
                    alert(data.message || '设置失败');
                }
            })
            .catch(err => {
                console.error(err);
                alert('设置失败');
            });
        }
        
        function deleteAddress(id) {
            if (!confirm('确定要删除此地址吗？')) return;
            const params = new URLSearchParams();
            params.append('_token', '{{ csrf_token() }}');
            params.append('_method', 'DELETE');
            
            fetch(`/addresses/${id}`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: params
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadAddressList();
                    refreshAddressList();
                } else {
                    alert(data.message || '删除失败');
                }
            })
            .catch(err => {
                console.error(err);
                alert('删除失败');
            });
        }
        
        function refreshAddressList() {
            fetch('{{ route("addresses.index") }}', { headers: { 'Accept': 'application/json' }})
            .then(r => r.json())
            .then(data => {
                let html = '';
                if (data.addresses && data.addresses.length > 0) {
                    html = '<div class="address-list">';
                    data.addresses.forEach(addr => {
                        html += `
                            <div class="address-item ${addr.is_default ? 'default' : ''}" data-id="${addr.id}" onclick="selectAddress(${addr.id})">
                                <div class="address-name">${addr.name}</div>
                                <div class="address-phone">${addr.phone}</div>
                                <div class="address-detail">${addr.province} ${addr.city} ${addr.district} ${addr.detail_address}</div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    document.getElementById('submit-btn').disabled = false;
                } else {
                    html = '<div class="no-address">暂无收货地址，<a href="javascript:void(0)" onclick="openAddressManage()">点击添加</a></div>';
                    document.getElementById('submit-btn').disabled = true;
                }
                document.getElementById('address-list-container').innerHTML = html;
                
                // 重新选中地址
                if (selectedAddressId > 0) {
                    document.querySelectorAll('.address-item').forEach(item => {
                        item.classList.toggle('selected', item.dataset.id == selectedAddressId);
                    });
                }
            });
        }
    </script>
</body>
</html>
