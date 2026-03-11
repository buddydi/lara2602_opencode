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
use App\Http\Controllers\FrontReviewController;
use App\Http\Controllers\FrontRefundController;
use App\Http\Controllers\FrontAfterSaleController;
use App\Http\Controllers\FrontInvoiceController;
use App\Http\Controllers\FrontPointsController;
use App\Http\Controllers\FrontNotificationController;
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

// 购物车页面（公开，游客可查看）
Route::get('/cart', [FrontCartController::class, 'index'])->name('cart.index');

Route::middleware('customer')->group(function () {
    // 购物车操作（需要登录）
    Route::post('/cart', [FrontCartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [FrontCartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [FrontCartController::class, 'destroy'])->name('cart.destroy');
    
    // 收货地址
    Route::resource('addresses', FrontAddressController::class);
    Route::post('/addresses/{address}/set-default', [FrontAddressController::class, 'setDefault'])->name('addresses.setDefault');
    
    // 订单
    Route::get('/orders', [FrontOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/checkout', [FrontOrderController::class, 'checkout'])->name('orders.checkout');
    Route::get('/orders/{order}', [FrontOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [FrontOrderController::class, 'store'])->name('orders.store');
    Route::patch('/orders/{order}/cancel', [FrontOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/pay', [FrontOrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{order}/pay', [FrontOrderController::class, 'processPayment'])->name('orders.processPayment');
    Route::post('/orders/{order}/receive', [FrontOrderController::class, 'receive'])->name('orders.receive');
    
    // 退款
    Route::get('/refunds', [FrontRefundController::class, 'index'])->name('refunds.index');
    Route::get('/orders/{order}/refund', [FrontRefundController::class, 'create'])->name('orders.refund.create');
    Route::post('/orders/{order}/refund', [FrontRefundController::class, 'store'])->name('orders.refund.store');
    
    // 发票
    Route::get('/invoices', [FrontInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/orders/{order}/invoice', [FrontInvoiceController::class, 'create'])->name('orders.invoice.create');
    Route::post('/orders/{order}/invoice', [FrontInvoiceController::class, 'store'])->name('orders.invoice.store');
    
    // 积分
    Route::get('/points', [FrontPointsController::class, 'index'])->name('points.index');
    
    // 售后服务
    Route::get('/after-sales', [FrontAfterSaleController::class, 'index'])->name('after-sales.index');
    Route::get('/after-sales/{afterSale}', [FrontAfterSaleController::class, 'show'])->name('after-sales.show');
    Route::get('/orders/{order}/after-sale/create', [FrontAfterSaleController::class, 'create'])->name('after-sales.create');
    Route::post('/orders/{order}/after-sale', [FrontAfterSaleController::class, 'store'])->name('after-sales.store');
    
    // 消息通知
    Route::get('/notifications', [FrontNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [FrontNotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/mark-all-read', [FrontNotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    
    // 物流跟踪
    Route::get('/orders/{order}/shipping', [\App\Http\Controllers\FrontShippingController::class, 'show'])->name('orders.shipping.show');
    
    // 评价
    Route::get('/orders/{order}/items/{item}/review', [FrontReviewController::class, 'create'])->name('orders.review.create');
    Route::post('/orders/{order}/items/{item}/review', [FrontReviewController::class, 'store'])->name('orders.review.store');
});

// 后台管理路由（需要 /admin 前缀）
Route::middleware('auth')->group(function () {
    // /admin 跳转到 /admin/dashboard
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // 后台首页
    Route::get('/admin/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');
    
    // 数据统计
    Route::get('/admin/statistics', [\App\Http\Controllers\Admin\DashboardController::class, 'statistics'])->name('admin.statistics');
    
    // 后台页面（统一 /admin 前缀）
    Route::prefix('admin')->as('admin.')->group(function () {
        Route::resource('posts', PostController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('users', UserController::class);
        
        // 客户管理
        Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'show', 'destroy']);
        
        // 订单管理
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update', 'destroy']);
        
        // 评价管理
        Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show', 'update', 'destroy']);
        
        // 退款管理
        Route::resource('refunds', \App\Http\Controllers\Admin\RefundController::class)->only(['index', 'show']);
        Route::post('/refunds/{refund}/approve', [\App\Http\Controllers\Admin\RefundController::class, 'approve'])->name('refunds.approve');
        Route::post('/refunds/{refund}/reject', [\App\Http\Controllers\Admin\RefundController::class, 'reject'])->name('refunds.reject');
        
        // 发票管理
        Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class)->only(['index', 'show']);
        Route::post('/invoices/{invoice}/issue', [\App\Http\Controllers\Admin\InvoiceController::class, 'issue'])->name('invoices.issue');
        
        // 积分管理
        Route::get('/points', [\App\Http\Controllers\Admin\PointsController::class, 'index'])->name('points.index');
        Route::get('/points/{customer}', [\App\Http\Controllers\Admin\PointsController::class, 'show'])->name('points.show');
        Route::post('/points/{customer}/add', [\App\Http\Controllers\Admin\PointsController::class, 'add'])->name('points.add');
        Route::post('/points/{customer}/deduct', [\App\Http\Controllers\Admin\PointsController::class, 'deduct'])->name('points.deduct');
        
        // 积分规则设置
        Route::get('/points-rules', [\App\Http\Controllers\Admin\PointsRuleController::class, 'index'])->name('points-rules.index');
        Route::post('/points-rules', [\App\Http\Controllers\Admin\PointsRuleController::class, 'update'])->name('points-rules.update');
        
        // 优惠券管理
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
        Route::post('/coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggle'])->name('coupons.toggle');
        
        // 消息管理
        Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class)->only(['index', 'create', 'store', 'destroy']);
        
        // 售后管理
        Route::resource('after-sales', \App\Http\Controllers\Admin\AfterSaleController::class)->only(['index', 'show', 'update']);
        
        // 商品模块
        Route::resource('product-categories', ProductCategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('product-attributes', ProductAttributeController::class);
        Route::resource('products', ProductController::class);
        Route::resource('products.skus', ProductSkuController::class);
        
        // 用户资料
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    
    Route::post('/admin/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::delete('/admin/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy');
});

require __DIR__.'/auth.php';
