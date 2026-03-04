<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReview;
use Illuminate\Http\Request;

class FrontReviewController extends Controller
{
    public function create(Order $order, OrderItem $item)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if ($order->status !== 'completed') {
            return back()->with('error', '只能在已完成订单中评价');
        }

        $existingReview = OrderReview::where('order_id', $order->id)
            ->where('order_item_id', $item->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', '该商品已评价');
        }

        return view('front.review.create', compact('order', 'item'));
    }

    public function store(Request $request, Order $order, OrderItem $item)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        if ($order->status !== 'completed') {
            return back()->with('error', '只能在已完成订单中评价');
        }

        $existingReview = OrderReview::where('order_id', $order->id)
            ->where('order_item_id', $item->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', '该商品已评价');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'nullable|max:500',
        ]);

        OrderReview::create([
            'order_id' => $order->id,
            'customer_id' => auth('customer')->id(),
            'product_id' => $item->product_id,
            'order_item_id' => $item->id,
            'rating' => $request->rating,
            'content' => $request->content,
            'status' => 1,
        ]);

        return redirect()->route('orders.show', $order)->with('success', '评价成功');
    }
}
