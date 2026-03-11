<?php

namespace App\Http\Controllers;

use App\Models\AfterSale;
use App\Models\Order;
use Illuminate\Http\Request;

class FrontAfterSaleController extends Controller
{
    public function index()
    {
        $afterSales = auth('customer')->user()
            ->afterSales()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('front.after-sale.index', compact('afterSales'));
    }

    public function create(Order $order)
    {
        if ($order->customer_id !== auth('customer')->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['shipped', 'completed'])) {
            return redirect()->route('orders.show', $order)->with('error', '该订单状态无法申请售后');
        }

        $hasPending = AfterSale::where('order_id', $order->id)
            ->where('customer_id', auth('customer')->id())
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($hasPending) {
            return redirect()->route('orders.show', $order)->with('error', '该订单已有待处理的售后申请');
        }

        return view('front.after-sale.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->customer_id !== auth('customer')->id()) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:return,exchange',
            'reason' => 'required|max:200',
            'description' => 'nullable|max:500',
        ]);

        $hasPending = AfterSale::where('order_id', $order->id)
            ->where('customer_id', auth('customer')->id())
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($hasPending) {
            return back()->with('error', '该订单已有待处理的售后申请');
        }

        AfterSale::create([
            'order_id' => $order->id,
            'customer_id' => auth('customer')->id(),
            'type' => $request->type,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('after-sales.index')->with('success', '售后申请已提交');
    }

    public function show(AfterSale $afterSale)
    {
        if ($afterSale->customer_id !== auth('customer')->id()) {
            abort(403);
        }

        return view('front.after-sale.show', compact('afterSale'));
    }
}
