<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;

class OrderController extends BaseApiController
{
    public function index(Request $request)
    {
        $orders = Order::with(['items', 'address'])
            ->where('customer_id', $request->user()->id)
            ->orderBy('id', 'desc')
            ->paginate($request->per_page ?? 15);

        return $this->success($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'remark' => 'nullable|string|max:500',
            'pay_method' => 'required|string',
        ]);

        $address = Address::findOrFail($data['address_id']);
        
        if ($address->customer_id !== $request->user()->id) {
            return $this->error('地址无效', 400);
        }

        $cartItems = \App\Models\CartItem::with(['product', 'sku'])
            ->where('customer_id', $request->user()->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return $this->error('购物车为空', 400);
        }

        $totalAmount = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $order = Order::create([
            'customer_id' => $request->user()->id,
            'address_id' => $address->id,
            'order_no' => 'ORD' . date('YmdHis') . rand(1000, 9999),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'remark' => $data['remark'] ?? null,
            'pay_method' => $data['pay_method'],
            'shipping_name' => $address->name,
            'shipping_phone' => $address->phone,
            'shipping_province' => $address->province,
            'shipping_city' => $address->city,
            'shipping_district' => $address->district,
            'shipping_address' => $address->detail_address,
        ]);

        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'sku_id' => $item->product_sku_id,
                'product_name' => $item->product->name,
                'sku_name' => $item->sku->name ?? null,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->price * $item->quantity,
            ]);
        }

        $cartItems->each->delete();

        return $this->success($order, '订单创建成功', 201);
    }

    public function show(Order $order, Request $request)
    {
        if ($order->customer_id !== $request->user()->id) {
            return $this->error('无权查看', 403);
        }

        $order->load(['items', 'address']);

        return $this->success($order);
    }

    public function cancel(Order $order, Request $request)
    {
        if ($order->customer_id !== $request->user()->id) {
            return $this->error('无权操作', 403);
        }

        if (!in_array($order->status, ['pending', 'paid'])) {
            return $this->error('订单状态不可取消', 400);
        }

        $order->update(['status' => 'cancelled']);

        return $this->success(null, '订单已取消');
    }
}
