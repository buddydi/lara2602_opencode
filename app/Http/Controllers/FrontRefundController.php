<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Refund;
use Illuminate\Http\Request;

class FrontRefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::where('customer_id', auth('customer')->id())
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('front.refund.index', compact('refunds'));
    }

    public function create(Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if (!in_array($order->status, ['paid', 'shipped'])) {
            return back()->with('error', '该订单无法申请退款');
        }

        $existingRefund = Refund::where('order_id', $order->id)->first();
        if ($existingRefund) {
            return back()->with('error', '该订单已有退款申请');
        }

        return view('front.refund.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if (!in_array($order->status, ['paid', 'shipped'])) {
            return back()->with('error', '该订单无法申请退款');
        }

        $existingRefund = Refund::where('order_id', $order->id)->first();
        if ($existingRefund) {
            return back()->with('error', '该订单已有退款申请');
        }

        $request->validate([
            'reason' => 'required|max:100',
            'description' => 'nullable|max:500',
        ]);

        Refund::create([
            'order_id' => $order->id,
            'customer_id' => auth('customer')->id(),
            'amount' => $order->pay_amount,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('orders.show', $order)->with('success', '退款申请已提交');
    }
}
