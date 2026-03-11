<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class FrontInvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('customer_id', auth('customer')->id())
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('front.invoice.index', compact('invoices'));
    }

    public function create(Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if (!in_array($order->status, ['paid', 'shipped', 'completed'])) {
            return back()->with('error', '该订单无法申请发票');
        }

        $existingInvoice = Invoice::where('order_id', $order->id)->first();
        if ($existingInvoice) {
            return back()->with('error', '该订单已申请过发票');
        }

        return view('front.invoice.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if (!in_array($order->status, ['paid', 'shipped', 'completed'])) {
            return back()->with('error', '该订单无法申请发票');
        }

        $existingInvoice = Invoice::where('order_id', $order->id)->first();
        if ($existingInvoice) {
            return back()->with('error', '该订单已申请过发票');
        }

        $request->validate([
            'type' => 'required|in:personal,company',
            'title' => 'required_if:type,company|max:100',
            'tax_no' => 'required_if:type,company|max:20',
            'email' => 'nullable|email',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:200',
        ]);

        Invoice::create([
            'order_id' => $order->id,
            'customer_id' => auth('customer')->id(),
            'type' => $request->type,
            'title' => $request->type === 'company' ? $request->title : '个人',
            'tax_no' => $request->tax_no,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'amount' => $order->pay_amount,
            'status' => 'pending',
        ]);

        return redirect()->route('orders.show', $order)->with('success', '发票申请已提交');
    }
}
