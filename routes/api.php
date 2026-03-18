<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\RoleApiController;
use App\Http\Controllers\Api\PermissionApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;

// 客户认证API（前台用户）
Route::prefix('customer')->group(function () {
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::post('login', [CustomerAuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [CustomerAuthController::class, 'me']);
        Route::put('profile', [CustomerAuthController::class, 'updateProfile']);
        Route::post('logout', [CustomerAuthController::class, 'logout']);
        
        // 收货地址
        Route::apiResource('addresses', AddressController::class);
        
        // 购物车
        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart', [CartController::class, 'store']);
        Route::put('cart/{cartItem}', [CartController::class, 'update']);
        Route::delete('cart/{cartItem}', [CartController::class, 'destroy']);
        Route::delete('cart', [CartController::class, 'clear']);
        
        // 订单
        Route::get('orders', [OrderController::class, 'index']);
        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders/{order}', [OrderController::class, 'show']);
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
    });
});

// 商品API（公开）
Route::get('products', [ProductController::class, 'index']);
Route::get('products/featured', [ProductController::class, 'featured']);
Route::get('products/search', [ProductController::class, 'search']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('products/category/{categoryId}', [ProductController::class, 'byCategory']);

// 分类API（公开）
Route::get('categories', [CategoryApiController::class, 'index']);
Route::get('categories-tree', [CategoryApiController::class, 'tree']);
Route::get('categories/{category}', [CategoryApiController::class, 'show']);

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('tokens', [AuthController::class, 'tokens']);
        Route::post('tokens', [AuthController::class, 'createToken']);
        Route::delete('tokens/{tokenId}', [AuthController::class, 'revokeToken']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostApiController::class);
    Route::get('my-posts', [PostApiController::class, 'myPosts']);
    
    Route::apiResource('categories', CategoryApiController::class);
    
    Route::apiResource('comments', CommentApiController::class);
    Route::get('my-comments', [CommentApiController::class, 'myComments']);
    Route::post('comments/{comment}/approve', [CommentApiController::class, 'approve']);
    Route::post('comments/{comment}/reject', [CommentApiController::class, 'reject']);
    
    Route::apiResource('roles', RoleApiController::class)->except(['create', 'edit'])->names('api.roles');
    Route::get('roles-permissions', [RoleApiController::class, 'permissions']);
    
    Route::apiResource('permissions', PermissionApiController::class)->except(['create', 'edit'])->names('api.permissions');
    Route::get('permissions-groups', [PermissionApiController::class, 'groups']);
    
    Route::apiResource('users', UserApiController::class)->names('api.users');
    Route::post('users/{user}/assign-role', [UserApiController::class, 'assignRole']);
    Route::post('users/{user}/revoke-role', [UserApiController::class, 'revokeRole']);
    Route::post('users/{user}/give-permission', [UserApiController::class, 'givePermission']);
    Route::post('users/{user}/revoke-permission', [UserApiController::class, 'revokePermission']);
});

Route::get('posts', [PostApiController::class, 'index']);
Route::get('posts/{post}', [PostApiController::class, 'show']);

Route::get('categories', [CategoryApiController::class, 'index']);
Route::get('categories-tree', [CategoryApiController::class, 'tree']);
Route::get('categories/{category}', [CategoryApiController::class, 'show']);

Route::get('comments', [CommentApiController::class, 'index']);
Route::get('comments/{comment}', [CommentApiController::class, 'show']);
