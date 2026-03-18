<?php

namespace App\Http\Controllers\Api;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends BaseApiController
{
    public function index(Request $request)
    {
        $cartItems = CartItem::with(['product', 'sku'])
            ->where('customer_id', $request->user()->id)
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return $this->success([
            'items' => $cartItems,
            'total' => $total,
            'count' => $cartItems->count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'sku_id' => 'nullable|exists:product_skus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);
        
        $price = (float) $product->price;
        $skuId = $data['sku_id'] ?? null;
        
        if ($skuId) {
            $sku = ProductSku::findOrFail($skuId);
            $price = (float) $sku->price;
        }

        $cartItem = CartItem::where('customer_id', $request->user()->id)
            ->where('product_id', $data['product_id'])
            ->where('product_sku_id', $skuId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            CartItem::create([
                'customer_id' => $request->user()->id,
                'product_id' => $data['product_id'],
                'product_sku_id' => $skuId,
                'quantity' => $data['quantity'],
                'price' => $price,
            ]);
        }

        return $this->success(null, '添加成功');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->customer_id !== $request->user()->id) {
            return $this->error('无权操作', 403);
        }

        $cartItem->update($data);

        return $this->success(null, '更新成功');
    }

    public function destroy(CartItem $cartItem, Request $request)
    {
        if ($cartItem->customer_id !== $request->user()->id) {
            return $this->error('无权操作', 403);
        }

        $cartItem->delete();

        return $this->success(null, '删除成功');
    }

    public function clear(Request $request)
    {
        CartItem::where('customer_id', $request->user()->id)->delete();

        return $this->success(null, '清空成功');
    }
}
