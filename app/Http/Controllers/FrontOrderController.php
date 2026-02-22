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

        $address = Address::findOrFail($request->address_id);
        if ($address->customer_id != auth('customer')->id()) {
            return back()->with('error', '无效的收货地址');
        }

        $cartItems = CartItem::whereIn('id', $request->cart_item_ids)
            ->where('customer_id', auth('customer')->id())
            ->with(['product', 'sku'])
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', '购物车为空');
        }

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
            'pay_amount' => $totalAmount,
            'product_count' => $productCount,
            'status' => 'pending',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        CartItem::whereIn('id', $request->cart_item_ids)->delete();

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

        return back()->with('success', '订单已取消');
    }
}
