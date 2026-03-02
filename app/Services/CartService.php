<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function addToCart(int $productId, int $skuId, int $quantity = 1): array
    {
        $sku = ProductSku::findOrFail($skuId);

        if ($sku->stock < $quantity) {
            return ['success' => false, 'message' => '库存不足'];
        }

        $customerId = auth('customer')->id();

        $cartItem = CartItem::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->where('product_sku_id', $skuId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $sku->stock) {
                return ['success' => false, 'message' => '库存不足'];
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'customer_id' => $customerId,
                'product_id' => $productId,
                'product_sku_id' => $skuId,
                'quantity' => $quantity,
            ]);
        }

        return [
            'success' => true,
            'message' => '已添加到购物车',
            'cart_count' => $this->getCartCount()
        ];
    }

    public function getCartItems()
    {
        return CartItem::with(['product', 'sku'])
            ->where('customer_id', auth('customer')->id())
            ->get();
    }

    public function getCartCount(): int
    {
        return CartItem::where('customer_id', auth('customer')->id())->sum('quantity');
    }

    public function getCartTotal(): float
    {
        $items = $this->getCartItems();
        return $items->sum(function ($item) {
            $price = $item->sku ? $item->sku->price : $item->product->price;
            return $price * $item->quantity;
        });
    }

    public function removeFromCart(int $cartId): bool
    {
        return CartItem::where('id', $cartId)
            ->where('customer_id', auth('customer')->id())
            ->delete() > 0;
    }

    public function updateQuantity(int $cartId, int $quantity): array
    {
        $cartItem = CartItem::with('sku')->findOrFail($cartId);

        if ($cartItem->customer_id != auth('customer')->id()) {
            return ['success' => false, 'message' => '无权操作'];
        }

        if ($quantity > $cartItem->sku->stock) {
            return ['success' => false, 'message' => '库存不足'];
        }

        if ($quantity <= 0) {
            $this->removeFromCart($cartId);
            return ['success' => true, 'message' => '已从购物车移除'];
        }

        CartItem::where('id', $cartId)->update(['quantity' => $quantity]);

        return ['success' => true, 'message' => '已更新数量'];
    }

    public function clearCart(): void
    {
        CartItem::where('customer_id', auth('customer')->id())->delete();
    }
}
