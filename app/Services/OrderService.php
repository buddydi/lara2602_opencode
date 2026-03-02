<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(int $customerId, array $orderData): Order
    {
        return DB::transaction(function () use ($customerId, $orderData) {
            $cartItems = CartItem::whereIn('id', $orderData['cart_item_ids'])
                ->where('customer_id', $customerId)
                ->with(['product', 'sku'])
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('购物车为空');
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

            $shippingFee = $orderData['shipping_fee'] ?? 0;
            $payAmount = $totalAmount + $shippingFee;

            $order = Order::create([
                'customer_id' => $customerId,
                'address_id' => $orderData['address_id'],
                'order_no' => Order::generateOrderNo(),
                'total_amount' => $totalAmount,
                'shipping_fee' => $shippingFee,
                'pay_amount' => $payAmount,
                'product_count' => $productCount,
                'status' => 'pending',
                'remark' => $orderData['remark'] ?? null,
                'shipping_method' => $orderData['shipping_method'] ?? 'standard',
                'payment_method' => $orderData['payment_method'] ?? 'online',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            foreach ($cartItems as $item) {
                if ($item->sku) {
                    $item->sku->decrement('stock', $item->quantity);
                    $item->sku->increment('sales', $item->quantity);
                }
                $item->product->increment('sales_count', $item->quantity);
            }

            CartItem::whereIn('id', $orderData['cart_item_ids'])->delete();

            return $order;
        });
    }

    public function cancelOrder(int $orderId, int $customerId): bool
    {
        $order = Order::where('id', $orderId)
            ->where('customer_id', $customerId)
            ->first();

        if (!$order) {
            return false;
        }

        if (!in_array($order->status, ['pending', 'paid'])) {
            return false;
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product_sku_id) {
                    ProductSku::where('id', $item->product_sku_id)->increment('stock', $item->quantity);
                }
            }
            $order->update(['status' => 'cancelled']);
        });

        return true;
    }

    public function payOrder(int $orderId, int $customerId, string $paymentMethod): bool
    {
        $order = Order::where('id', $orderId)
            ->where('customer_id', $customerId)
            ->first();

        if (!$order || $order->status !== 'pending') {
            return false;
        }

        $order->update([
            'status' => 'paid',
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
        ]);

        return true;
    }

    public function confirmReceive(int $orderId, int $customerId): bool
    {
        $order = Order::where('id', $orderId)
            ->where('customer_id', $customerId)
            ->first();

        if (!$order || $order->status !== 'shipped') {
            return false;
        }

        $order->update(['status' => 'completed']);

        return true;
    }
}
