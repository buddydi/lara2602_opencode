<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSkuController;
use App\Http\Controllers\FrontHomeController;
use App\Http\Controllers\FrontCartController;
use App\Http\Controllers\FrontAddressController;
use App\Http\Controllers\FrontOrderController;
use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontHomeController::class, 'index'])->name('home');
Route::get('/products', [FrontHomeController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [FrontHomeController::class, 'productDetail'])->name('products.detail');

// 顾客认证路由
Route::middleware('guest:customer')->group(function () {
    Route::get('/customer/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/customer/login', [CustomerAuthController::class, 'login']);
    Route::get('/customer/register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
    Route::post('/customer/register', [CustomerAuthController::class, 'register']);
});
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

Route::middleware('customer')->group(function () {
    // 购物车
    Route::get('/cart', [FrontCartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [FrontCartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [FrontCartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [FrontCartController::class, 'destroy'])->name('cart.destroy');
    
    // 收货地址
    Route::resource('addresses', FrontAddressController::class);
    
    // 订单
    Route::get('/orders', [FrontOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [FrontOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [FrontOrderController::class, 'store'])->name('orders.store');
    Route::patch('/orders/{order}/cancel', [FrontOrderController::class, 'cancel'])->name('orders.cancel');
});

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    
    // 商品模块（后台）
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::resource('product-categories', ProductCategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('product-attributes', ProductAttributeController::class);
        Route::resource('products', ProductController::class);
        Route::resource('products.skus', ProductSkuController::class);
    });
    
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
