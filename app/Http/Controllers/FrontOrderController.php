<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Http\Request;

class FrontOrderController extends Controller
{
    public function index()
    {
        $orders = auth('customer')->user()->orders()->with('items')->latest()->paginate(10);
        return view('front.order.index', compact('orders'));
    }

    public function checkout(Request $request)
    {
        $cartItemIds = $request->cart_item_ids ?? [];
        
        if (is_string($cartItemIds)) {
            $cartItemIds = array_filter(array_map('intval', explode(',', $cartItemIds)));
        } elseif (is_array($cartItemIds)) {
            $cartItemIds = array_filter(array_map('intval', $cartItemIds));
        } else {
            $cartItemIds = [];
        }
        
        if (empty($cartItemIds)) {
            return redirect()->route('cart.index')->with('error', '请选择要结算的商品');
        }

        $cartItems = CartItem::whereIn('id', $cartItemIds)
            ->where('customer_id', auth('customer')->id())
            ->with(['product', 'sku'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', '购物车为空');
        }

        $addresses = auth('customer')->user()->addresses()->orderBy('is_default', 'desc')->get();
        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->sku ? $item->sku->price : $item->product->price;
            return $price * $item->quantity;
        });

        return view('front.order.checkout', compact('cartItems', 'addresses', 'subtotal'));
    }

    public function show(Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }
        $order->load('items', 'address');
        return view('front.order.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart_item_ids' => 'required|array',
            'address_id' => 'required|exists:addresses,id',
        ]);

        $cartItemIds = is_array($request->cart_item_ids) ? $request->cart_item_ids : array_filter([$request->cart_item_ids]);

        $address = Address::findOrFail($request->address_id);
        if ($address->customer_id != auth('customer')->id()) {
            return back()->with('error', '无效的收货地址');
        }

        $cartItems = CartItem::whereIn('id', $cartItemIds)
            ->where('customer_id', auth('customer')->id())
            ->with(['product', 'sku'])
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', '购物车为空');
        }

        $shippingMethod = $request->shipping_method ?? 'standard';
        $shippingFee = $shippingMethod === 'express' ? 10 : 0;

        $totalAmount = 0;
        $productCount = 0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $price = $item->sku ? $item->sku->price : $item->product->price;
            $subtotal = $price * $item->quantity;
            $totalAmount += $subtotal;
            $productCount += $item->quantity;

            $orderItems[] = [
                'product_id' => $item->product_id,
                'product_sku_id' => $item->product_sku_id,
                'product_name' => $item->product->name,
                'product_image' => $item->product->cover_image,
                'sku_name' => $item->sku ? $item->sku->name : null,
                'price' => $price,
                'quantity' => $item->quantity,
                'total' => $subtotal,
            ];
        }

        $order = Order::create([
            'customer_id' => auth('customer')->id(),
            'address_id' => $address->id,
            'order_no' => Order::generateOrderNo(),
            'total_amount' => $totalAmount,
            'shipping_fee' => $shippingFee,
            'freight' => $shippingFee,
            'pay_amount' => $totalAmount + $shippingFee,
            'product_count' => $productCount,
            'status' => 'pending',
            'shipping_method' => $shippingMethod,
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        CartItem::whereIn('id', $cartItemIds)->delete();

        return redirect()->route('orders.show', $order)->with('success', '订单创建成功');
    }

    public function cancel(Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if (!in_array($order->status, ['pending', 'paid'])) {
            return back()->with('error', '该订单无法取消');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')->with('success', '订单已取消');
    }

    public function pay(Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('error', '该订单无法支付');
        }

        return view('front.order.pay', compact('order'));
    }

    public function processPayment(Request $request, Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', '该订单无法支付');
        }

        $request->validate([
            'pay_method' => 'required|in:alipay,wechat,balance',
        ]);

        $order->update([
            'status' => 'paid',
            'pay_method' => $request->pay_method,
            'paid_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)->with('success', '支付成功');
    }

    public function receive(Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if ($order->status !== 'shipped') {
            return back()->with('error', '该订单无法确认收货');
        }

        $order->update(['status' => 'completed']);

        return redirect()->route('orders.show', $order)->with('success', '确认收货成功');
    }
}
