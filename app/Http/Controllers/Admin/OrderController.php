<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'address'])->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'address', 'items', 'reviews']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'shipping_company' => 'nullable|max:50',
            'shipping_no' => 'nullable|max:50',
        ]);

        if ($request->shipping_no && !$order->shipping_no) {
            $order->update([
                'shipping_company' => $request->shipping_company,
                'shipping_no' => $request->shipping_no,
                'status' => 'shipped',
                'shipped_at' => now(),
            ]);
            return redirect()->route('admin.orders.show', $order)->with('success', '发货成功');
        }

        $order->update($request->only(['shipping_company', 'shipping_no']));
        return redirect()->route('admin.orders.show', $order)->with('success', '更新成功');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', '订单删除成功');
    }
}
