<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class FrontShippingController extends Controller
{
    public function show(Order $order)
    {
        if ($order->customer_id != auth('customer')->id()) {
            return back()->with('error', '无权操作');
        }

        $traces = [];
        
        if ($order->shipping_no && $order->shipping_company) {
            $traces = $this->getMockTraces($order);
        }

        return view('front.shipping.show', compact('order', 'traces'));
    }

    private function getMockTraces($order)
    {
        $traces = [];
        
        $traces[] = [
            'time' => $order->shipped_at ? $order->shipped_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
            'status' => '已发货',
            'content' => '商家已发货，商品已交付给物流公司',
        ];
        
        $traces[] = [
            'time' => $order->shipped_at ? $order->shipped_at->copy()->addHours(2)->format('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime('+2 hours')),
            'status' => '运输中',
            'content' => '物流公司已收取包裹，运输中',
        ];
        
        return $traces;
    }
}
