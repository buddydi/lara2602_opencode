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
use App\Http\Controllers\FrontVipController;
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
    
    // VIP会员
    Route::get('/vip', [FrontVipController::class, 'index'])->name('vip.index');
    
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
        
        // 会员管理
        Route::get('/members/levels', [\App\Http\Controllers\Admin\MemberController::class, 'levelIndex'])->name('members.levels');
        Route::put('/members/levels', [\App\Http\Controllers\Admin\MemberController::class, 'levelUpdate'])->name('members.levels.update');
        Route::resource('members', \App\Http\Controllers\Admin\MemberController::class);
        
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
        
        // 操作日志
        Route::resource('activity-logs', \App\Http\Controllers\Admin\ActivityLogController::class)->only(['index', 'show', 'destroy']);
        Route::post('/activity-logs/clear', [\App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('activity-logs.clear');
        
        // 库存管理
        Route::get('/stock', [\App\Http\Controllers\Admin\StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/logs', [\App\Http\Controllers\Admin\StockController::class, 'logs'])->name('stock.logs');
        Route::get('/stock/create-log', [\App\Http\Controllers\Admin\StockController::class, 'createLog'])->name('stock.create-log');
        Route::post('/stock/store-log', [\App\Http\Controllers\Admin\StockController::class, 'storeLog'])->name('stock.store-log');
        Route::get('/stock/alerts', [\App\Http\Controllers\Admin\StockController::class, 'alerts'])->name('stock.alerts');
        Route::get('/stock/create-alert', [\App\Http\Controllers\Admin\StockController::class, 'createAlert'])->name('stock.create-alert');
        Route::post('/stock/store-alert', [\App\Http\Controllers\Admin\StockController::class, 'storeAlert'])->name('stock.store-alert');
        Route::get('/stock/edit-alert/{stockAlert}', [\App\Http\Controllers\Admin\StockController::class, 'editAlert'])->name('stock.edit-alert');
        Route::put('/stock/update-alert/{stockAlert}', [\App\Http\Controllers\Admin\StockController::class, 'updateAlert'])->name('stock.update-alert');
        Route::delete('/stock/destroy-alert/{stockAlert}', [\App\Http\Controllers\Admin\StockController::class, 'destroyAlert'])->name('stock.destroy-alert');
        Route::get('/stock/get-skus', [\App\Http\Controllers\Admin\StockController::class, 'getSkus'])->name('stock.get-skus');
        
        // 促销活动
        Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
        Route::post('/promotions/{promotion}/toggle', [\App\Http\Controllers\Admin\PromotionController::class, 'toggle'])->name('promotions.toggle');
        
        // 系统设置
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/payment', [\App\Http\Controllers\Admin\SettingsController::class, 'paymentIndex'])->name('settings.payment');
        Route::post('/settings/payment', [\App\Http\Controllers\Admin\SettingsController::class, 'paymentStore'])->name('settings.payment.store');
        Route::put('/settings/payment/{payment}', [\App\Http\Controllers\Admin\SettingsController::class, 'paymentUpdate'])->name('settings.payment.update');
        Route::delete('/settings/payment/{payment}', [\App\Http\Controllers\Admin\SettingsController::class, 'paymentDestroy'])->name('settings.payment.destroy');
        Route::get('/settings/shipping', [\App\Http\Controllers\Admin\SettingsController::class, 'shippingIndex'])->name('settings.shipping');
        Route::post('/settings/shipping', [\App\Http\Controllers\Admin\SettingsController::class, 'shippingStore'])->name('settings.shipping.store');
        Route::put('/settings/shipping/{shipping}', [\App\Http\Controllers\Admin\SettingsController::class, 'shippingUpdate'])->name('settings.shipping.update');
        Route::delete('/settings/shipping/{shipping}', [\App\Http\Controllers\Admin\SettingsController::class, 'shippingDestroy'])->name('settings.shipping.destroy');
        Route::get('/settings/shop', [\App\Http\Controllers\Admin\SettingsController::class, 'shop'])->name('settings.shop');
        Route::put('/settings/shop', [\App\Http\Controllers\Admin\SettingsController::class, 'shopUpdate'])->name('settings.shop.update');
        
        // API 接口管理
        Route::get('/api-endpoints', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'index'])->name('api-endpoints.index');
        Route::get('/api-endpoints/create', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'create'])->name('api-endpoints.create');
        Route::post('/api-endpoints', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'store'])->name('api-endpoints.store');
        Route::get('/api-endpoints/{apiEndpoint}', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'show'])->name('api-endpoints.show');
        Route::get('/api-endpoints/{apiEndpoint}/edit', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'edit'])->name('api-endpoints.edit');
        Route::put('/api-endpoints/{apiEndpoint}', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'update'])->name('api-endpoints.update');
        Route::delete('/api-endpoints/{apiEndpoint}', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'destroy'])->name('api-endpoints.destroy');
        Route::post('/api-endpoints/{apiEndpoint}/toggle', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'toggle'])->name('api-endpoints.toggle');
        Route::get('/api-endpoints/sync', [\App\Http\Controllers\Admin\ApiEndpointController::class, 'sync'])->name('api-endpoints.sync');
        
        // API Token 管理
        Route::get('/api-tokens', [\App\Http\Controllers\Admin\ApiTokenController::class, 'index'])->name('api-tokens.index');
        Route::get('/api-tokens/create', [\App\Http\Controllers\Admin\ApiTokenController::class, 'create'])->name('api-tokens.create');
        Route::post('/api-tokens', [\App\Http\Controllers\Admin\ApiTokenController::class, 'store'])->name('api-tokens.store');
        Route::get('/api-tokens/{apiToken}', [\App\Http\Controllers\Admin\ApiTokenController::class, 'show'])->name('api-tokens.show');
        Route::get('/api-tokens/{apiToken}/edit', [\App\Http\Controllers\Admin\ApiTokenController::class, 'edit'])->name('api-tokens.edit');
        Route::put('/api-tokens/{apiToken}', [\App\Http\Controllers\Admin\ApiTokenController::class, 'update'])->name('api-tokens.update');
        Route::delete('/api-tokens/{apiToken}', [\App\Http\Controllers\Admin\ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
        Route::post('/api-tokens/{apiToken}/toggle', [\App\Http\Controllers\Admin\ApiTokenController::class, 'toggle'])->name('api-tokens.toggle');
        Route::post('/api-tokens/{apiToken}/regenerate', [\App\Http\Controllers\Admin\ApiTokenController::class, 'regenerate'])->name('api-tokens.regenerate');
        Route::post('/api-tokens/test', [\App\Http\Controllers\Admin\ApiTokenController::class, 'test'])->name('api-tokens.test');
        
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
