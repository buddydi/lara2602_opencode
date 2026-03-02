# Laravel 12 商城项目开发指南 (PDR)

## 文档信息

| 项目 | 内容 |
|------|------|
| 目标读者 | 3年PHP原生开发经验，无Laravel经验 |
| 框架版本 | Laravel 12 |
| PHP版本 | PHP 8.2+ |
| 参考项目 | Aimeos、Bagisto、BeikeShop |

## 本地开发环境（本项目使用 phpstudy PRO）

| 项目 | 值 |
|------|-----|
| PHP 版本 | 8.2.9 |
| 操作系统 | Windows 10 (AMD64) |
| Web服务器 | phpstudy PRO |
| 服务器API | CGI/FastCGI |
| php.ini | `D:\phpstudy_pro\Extensions\php\php8.2.9nts\php.ini` |
| 访问地址 | http://test.lara2602.local/ |

---

## 一、项目概述

本文档为拥有3年PHP原生开发经验、但无Laravel经验的开发者编写，指导其使用Laravel 12从零开始构建一个功能完善的电商商城系统。

### 1.1 项目目标

参考 Aimeos、Bagisto、BeikeShop 三个开源电商系统的优秀特性，创建一个具备以下功能的商城系统：

- 商品管理（产品、分类、品牌、属性、SKU）
- 购物车与结账流程
- 订单管理
- 客户系统（注册、登录、地址、收藏夹）
- 支付与配送
- 后台管理系统
- 插件系统
- 多语言支持

### 1.2 技术选型

| 层级 | 技术 | 说明 |
|------|------|------|
| 后端框架 | Laravel 12 | 最新LTS版本 |
| PHP版本 | PHP 8.2+ | 必须使用PHP 8.2及以上 |
| 前端构建 | Vite | Laravel 12默认集成 |
| CSS框架 | Tailwind CSS 4.0 | 现代CSS框架 |
| 数据库 | MySQL 8.0 | 推荐使用MySQL |
| 认证 | Laravel Breeze | 官方认证脚手架 |
| API认证 | Laravel Sanctum | Token认证 |
| 权限管理 | Spatie Permission | 角色权限管理 |

---

## 二、开发路线图（总览）

```
【第一阶段：Web商城】
阶段一：环境搭建与基础配置（预计1天）
    ↓
阶段二：Laravel 12 核心概念学习（预计2天）
    ↓
阶段三：数据库设计与实现（预计2天）
    ↓
阶段四：核心功能开发 - 商品模块（预计3天）
    ↓
阶段五：核心功能开发 - 购物车与订单（预计3天）
    ↓
阶段六：核心功能开发 - 用户系统（预计2天）
    ↓
阶段七：后台管理系统开发（预计4天）
    ↓
阶段八：高级功能 - 发票/退款/优惠券（预计2天）
    ↓
阶段九：高级功能 - 多语言/多货币/多站点（预计2天）
    ↓
阶段十：高级功能 - CMS/页面装修/SEO（预计2天）
    ↓
阶段十一：高级功能 - 数据导入导出/订阅（预计1天）
    ↓
阶段十二：前端页面开发（预计4天）
    ↓
阶段十三：插件系统开发（预计3天）
    ↓
阶段十四：测试与部署（预计2天）

【第二阶段：移动端对接】
阶段十五：小程序/App API对接（预计3天）
    ↓
阶段十六：Next.js多端前端（预计5天）
    ↓
阶段十七：第三方平台对接（预计2天）
```

---

## 路由治理与端点划分

### 目的
明确客户端、游客端、管理员端路由的差异、前缀与认证策略，确保阶段四及后续开发在端点层面有清晰的治理边界。

### 端点分类与归属

#### 1. 客户端/前端用户端点（需认证，使用 customer.guard）

| 端点 | 路径 | 说明 |
|------|------|------|
| 客户登录 | /customer/login | 需认证 |
| 客户注册 | /customer/register | 需认证 |
| 客户退出 | /customer/logout | 需认证 |
| 购物车 | /cart | 需认证 |
| 购物车操作 | /cart/{id} (PATCH/DELETE) | 需认证 |
| 收货地址 | /addresses | 需认证 |
| 订单 | /orders | 需认证 |
| 商品浏览 | /products、/products/{product} | 公开 |

#### 2. 游客端端点（Guest，公开）

| 端点 | 路径 | 说明 |
|------|------|------|
| 商品列表 | /products | 公开 |
| 商品详情 | /products/{product} | 公开 |
| 分类列表 | /categories | 公开 |
| 分类详情 | /categories/{category} | 公开 |

#### 3. 管理端（Admin，/admin 前缀，需认证）

| 端点 | 路径 | 说明 |
|------|------|------|
| 后台登录 | /admin/login | 需认证 |
| 商品管理 | /admin/products | 需认证 |
| 分类管理 | /admin/product-categories | 需认证 |
| 品牌管理 | /admin/brands | 需认证 |
| 属性管理 | /admin/product-attributes | 需认证 |
| SKU管理 | /admin/product-skus | 需认证 |
| 订单管理 | /admin/orders | 需认证 |
| 客户管理 | /admin/customers | 需认证 |
| 系统设置 | /admin/settings | 需认证 |

### 路由前缀与 Guard 对照

| 端点类型 | 路由前缀 | Guard | 认证方式 |
|----------|----------|-------|----------|
| 客户端 | /customer/xxx | customer | Session |
| 游客端 | / (无前缀) | web/guest | 公开 |
| 管理端 | /admin/xxx | admin | Session + 权限控制 |

### 变更治理规则

凡涉及路由/端点相关的变更，必须遵循以下规则：

1. **变更记录**：所有路由变更需记录到 PRD，包含：
   - 变更原因
   - 影响范围（哪些端点受影响）
   - 验收标准
   - 审核人

2. **端点命名规范**：
   - 客户端路由：`/customer/xxx`
   - 管理端路由：`/admin/xxx`
   - 公开路由：无前缀或 `/api/xxx`

3. **认证要求**：
   - 管理端必须使用 `/admin` 前缀
   - 客户端认证使用 `customer` guard
   - 游客端尽量保持公开

### 已完成阶段三的端点治理（示例）

- 客户端/游客端：商品浏览、注册/登录、购物车入口、地址/订单入口等
- 管理端：后台路由带 /admin 前缀，管理员相关功能独立路由，配合权限系统

---

## 三、阶段详解

### 阶段一：环境搭建与基础配置

**预计时间：1天**

#### 1.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 1.1.1 | 安装 PHP 8.2+ | PHP环境 |
| 1.1.2 | 安装 Composer | PHP依赖管理工具 |
| 1.1.3 | 安装 MySQL 8.0 | 数据库环境 |
| 1.1.4 | 安装 Node.js 20+ | 前端构建环境 |
| 1.1.5 | 创建 Laravel 12 项目 | 项目骨架 |

#### 1.2 具体操作

**步骤1：安装PHP 8.2+**

Windows用户推荐使用XAMPP或Laragon，Mac用户推荐使用Valet，Linux用户推荐使用Laravel Herd。

**步骤2：安装Composer**

```bash
# 下载Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

**步骤3：创建Laravel 12项目**

```bash
# 创建Laravel 12项目
composer create-project laravel/laravel newshop "12.*"

# 进入项目目录
cd newshop
```

**步骤4：安装必要依赖**

```bash
# 安装Breeze认证脚手架
composer require laravel/breeze --dev

# 安装Sanctum API认证
composer require laravel/sanctum

# 安装权限管理
composer require spatie/laravel-permission
```

**步骤5：配置.env文件**

```bash
# 复制环境配置文件
cp .env.example .env

# 生成应用密钥
php artisan key:generate
```

```env
# .env 文件配置
APP_NAME=NewShop
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=newshop_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

**步骤6：启动项目**

```bash
# 安装前端依赖
npm install

# 启动开发服务器
php artisan serve
```

#### 1.3 注意事项

- Windows用户推荐使用XAMPP或Laragon一键配置
- Mac用户推荐使用Laravel Valet
- Linux用户推荐使用Laravel Herd

---

### 阶段二：Laravel 12 核心概念学习

**预计时间：2天**

#### 2.1 关键概念（必须掌握）

作为有3年PHP经验的开发者，你需要快速理解Laravel与原生PHP的区别：

| 概念 | 原生PHP对比 | Laravel优势 |
|------|-------------|-------------|
| 路由 | 手动解析URL | 自动路由映射 |
| 数据库 | mysqli/PDO | Eloquent ORM简洁优雅 |
| 模板 | PHP标签混合 | Blade模板引擎 |
| 认证 | 手写session/cookie | 完善的Auth系统 |
| 依赖 | 手动include | Composer自动管理 |

#### 2.2 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 2.1.1 | 学习路由系统 | 理解路由定义方式 |
| 2.1.2 | 学习控制器 | 能创建基础控制器 |
| 2.1.3 | 学习Eloquent ORM | 能进行CURD操作 |
| 2.1.4 | 学习Migrations | 能创建数据表 |
| 2.1.5 | 学习Blade模板 | 能创建视图 |

#### 2.3 快速入门示例

**创建控制器：**

```bash
# 创建产品控制器
php artisan make:controller ProductController
```

**定义路由 routes/web.php：**

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// 路由定义
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
```

**创建模型与迁移：**

```bash
# 创建产品模型（同时创建迁移文件）
php artisan make:model Product -m
```

**运行迁移：**

```bash
# 执行迁移创建数据表
php artisan migrate

# 查看迁移状态
php artisan migrate:status
```

#### 2.4 必学的Laravel核心

1. **Artisan命令行** - Laravel自带的高效命令行工具
2. **服务容器** - Laravel的依赖注入核心
3. **中间件** - 请求过滤和处理
4. **服务提供者** - 启动配置的核心
5. **Facades** - 静态调用服务的方式

---

### 阶段三：数据库设计与实现

**预计时间：2天**

#### 3.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 3.1.1 | 设计数据库ER图 | 数据库设计文档 |
| 3.1.2 | 创建核心迁移文件 | 数据表结构 |
| 3.1.3 | 创建模型 | Eloquent模型 |
| 3.1.4 | 创建种子数据 | 测试数据 |

#### 3.2 数据库设计（参考三个电商系统）

参考Aimeos的MShop模块化设计、Bagisto的35+模块、BeikeShop的45个模型，设计以下核心表：

**核心数据表：**

| 表名 | 说明 |
|------|------|
| products | 产品主表 |
| product_descriptions | 产品描述（多语言） |
| product_skus | SKU规格表 |
| product_images | 产品图片 |
| product_videos | 产品视频 |
| product_attributes | 产品属性关联 |
| product_categories | 产品分类关联 |
| product_relations | 产品关联（关联产品） |
| product_tags | 产品标签关联 |
| product_views | 产品浏览记录 |
| categories | 分类表 |
| category_descriptions | 分类描述 |
| category_images | 分类图片 |
| brands | 品牌表 |
| brand_images | 品牌图片 |
| attributes | 属性表 |
| attribute_values | 属性值表 |
| attribute_groups | 属性组 |
| customers | 客户表 |
| customer_addresses | 客户地址 |
| customer_groups | 客户组 |
| customer_wishlists | 心愿单/收藏夹 |
| customer_reviews | 产品评价 |
| orders | 订单主表 |
| order_products | 订单商品 |
| order_addresses | 订单地址 |
| order_totals | 订单金额汇总 |
| order_histories | 订单历史 |
| order_payments | 订单支付记录 |
| order_shipments | 订单发货记录 |
| order_invoices | 订单发票 |
| order_refunds | 订单退款 |
| carts | 购物车 |
| cart_items | 购物车商品 |
| payments | 支付记录 |
| shipments | 发货记录 |
| invoices | 发票主表 |
| refunds | 退款主表 |
| coupons | 优惠券 |
| coupon_usage | 优惠券使用记录 |
| subscriptions | 订阅表 |
| pages | 文章/CMS页面 |
| page_categories | 文章分类 |
| page_descriptions | 文章描述 |
| themes | 主题配置 |
| theme_settings | 主题设置 |
| languages | 语言 |
| currencies | 货币 |
| currency_rates | 货币汇率 |
| channels | 销售渠道 |
| tax_classes | 税类 |
| tax_rates | 税率 |
| inventory_sources | 库存源/仓库 |
| inventory_stock | 库存明细 |
| wishlists | 收藏夹 |
| reviews | 产品评价 |
| tags | 标签 |
| settings | 系统配置 |
| notifications | 通知记录 |
| url_aliases | URL别名/SEO |
| zones | 地区/省份 |
| regions | 区域 |
| countries | 国家 |

#### 3.3 创建迁移文件

```bash
# 创建产品表迁移
php artisan make:migration create_products_table

# 创建分类表迁移
php artisan make:migration create_categories_table

# 创建客户表迁移
php artisan make:migration create_customers_table

# 创建订单表迁移
php artisan make:migration create_orders_table
```

**示例：产品表迁移**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('sales_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

#### 3.4 创建模型

```bash
# 创建产品模型（带迁移）
php artisan make:model Product -m

# 创建带控制器和资源的模型
php artisan make:model Product -mcr
```

**模型关联示例：**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'price',
        'original_price',
        'stock',
        'sales_count',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // 产品关联SKU
    public function skus(): HasMany
    {
        return $this->hasMany(ProductSku::class);
    }

    // 产品关联图片
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    // 产品关联分类
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }
}
```

---

### 阶段四：核心功能开发 - 商品模块

**预计时间：3天**

#### 4.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 4.1.1 | 创建产品相关模型 | Product、ProductSku等 |
| 4.1.2 | 创建产品仓库(Repository) | ProductRepository |
| 4.1.3 | 开发产品列表API | GET /api/products |
| 4.1.4 | 开发产品详情API | GET /api/products/{id} |
| 4.1.5 | 开发分类管理 | CRUD功能 |
| 4.1.6 | 开发品牌管理 | CRUD功能 |
| 4.1.7 | 开发属性管理 | 属性与属性值 |

#### 4.2 Repository模式实现

参考BeikeShop的Repository设计模式：

```php
<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    /**
     * 获取所有产品（带分页）
     */
    public function getAllProducts(int $perPage = 15)
    {
        return Product::with(['category', 'skus', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * 获取产品详情
     */
    public function getProductById(int $id): ?Product
    {
        return Product::with(['category', 'skus', 'attributes', 'images'])
            ->find($id);
    }

    /**
     * 获取产品详情（通过SKU）
     */
    public function getProductBySku(string $sku): ?Product
    {
        return Product::with(['skus', 'images'])
            ->where('sku', $sku)
            ->first();
    }

    /**
     * 按分类获取产品
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 15)
    {
        return Product::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })->paginate($perPage);
    }

    /**
     * 搜索产品
     */
    public function searchProducts(string $keyword, int $perPage = 15)
    {
        return Product::where('name', 'like', "%{$keyword}%")
            ->orWhere('sku', 'like', "%{$keyword}%")
            ->paginate($perPage);
    }

    /**
     * 获取精选产品
     */
    public function getFeaturedProducts(int $limit = 10)
    {
        return Product::with(['images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->limit($limit)
            ->get();
    }
}
```

#### 4.3 控制器开发

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * 产品列表
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $products = $this->productRepository->getAllProducts($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * 产品详情
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->getProductById($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => '产品不存在'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * 按分类获取产品
     */
    public function byCategory(Request $request, int $categoryId): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $products = $this->productRepository->getProductsByCategory($categoryId, $perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * 搜索产品
     */
    public function search(Request $request): JsonResponse
    {
        $keyword = $request->input('q', '');
        
        if (strlen($keyword) < 2) {
            return response()->json([
                'success' => false,
                'message' => '搜索关键词至少2个字符'
            ], 400);
        }

        $products = $this->productRepository->searchProducts($keyword);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * 精选产品
     */
    public function featured(): JsonResponse
    {
        $products = $this->productRepository->getFeaturedProducts(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
```

#### 4.4 路由定义

```php
<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;

// 产品路由
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/featured', [ProductController::class, 'featured']);
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/category/{categoryId}', [ProductController::class, 'byCategory']);
});

// 分类路由
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::get('/{id}/products', [CategoryController::class, 'products']);
});
```

---

### 阶段五：核心功能开发 - 购物车与订单

**预计时间：3天**

#### 5.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 5.1.1 | 购物车功能 | 加入/删除/更新商品 |
| 5.1.2 | 购物车页面 | 显示购物车内容 |
| 5.1.3 | 结算流程 | 地址选择、配送方式 |
| 5.1.4 | 订单创建 | 创建订单、扣减库存 |
| 5.1.5 | 订单支付 | 支付流程集成 |
| 5.1.6 | 订单管理 | 查看订单状态 |

#### 5.2 购物车实现

参考Bagisto的Checkout模块设计：

```php
<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductSku;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * 添加产品到购物车
     */
    public function addToCart(int $skuId, int $quantity = 1): array
    {
        $sku = ProductSku::findOrFail($skuId);

        // 检查库存
        if ($sku->stock < $quantity) {
            return [
                'success' => false,
                'message' => '库存不足'
            ];
        }

        $userId = Auth::id();

        $cart = Cart::where('customer_id', $userId)
            ->where('sku_id', $skuId)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $quantity;
            
            if ($newQuantity > $sku->stock) {
                return [
                    'success' => false,
                    'message' => '库存不足'
                ];
            }

            $cart->quantity = $newQuantity;
            $cart->save();
        } else {
            Cart::create([
                'customer_id' => $userId,
                'sku_id' => $skuId,
                'quantity' => $quantity,
                'price' => $sku->price
            ]);
        }

        return [
            'success' => true,
            'message' => '已添加到购物车',
            'cart_count' => $this->getCartCount()
        ];
    }

    /**
     * 获取购物车商品
     */
    public function getCartItems()
    {
        return Cart::with(['sku.product', 'sku.images'])
            ->where('customer_id', Auth::id())
            ->get();
    }

    /**
     * 获取购物车数量
     */
    public function getCartCount(): int
    {
        return Cart::where('customer_id', Auth::id())->sum('quantity');
    }

    /**
     * 获取购物车总价
     */
    public function getCartTotal(): float
    {
        $items = $this->getCartItems();
        return $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * 从购物车移除
     */
    public function removeFromCart(int $cartId): bool
    {
        return Cart::where('id', $cartId)
            ->where('customer_id', Auth::id())
            ->delete() > 0;
    }

    /**
     * 更新购物车数量
     */
    public function updateQuantity(int $cartId, int $quantity): array
    {
        $cart = Cart::with('sku')->findOrFail($cartId);

        if ($quantity > $cart->sku->stock) {
            return [
                'success' => false,
                'message' => '库存不足'
            ];
        }

        if ($quantity <= 0) {
            $this->removeFromCart($cartId);
            return [
                'success' => true,
                'message' => '已从购物车移除'
            ];
        }

        Cart::where('id', $cartId)->update(['quantity' => $quantity]);

        return [
            'success' => true,
            'message' => '已更新数量'
        ];
    }

    /**
     * 清空购物车
     */
    public function clearCart(): void
    {
        Cart::where('customer_id', Auth::id())->delete();
    }
}
```

#### 5.3 购物车控制器

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * 获取购物车列表
     */
    public function index(): JsonResponse
    {
        $items = $this->cartService->getCartItems();
        $total = $this->cartService->getCartTotal();

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'total' => $total,
                'count' => $items->count()
            ]
        ]);
    }

    /**
     * 添加到购物车
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sku_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $result = $this->cartService->addToCart(
            $validated['sku_id'],
            $validated['quantity']
        );

        return response()->json($result);
    }

    /**
     * 更新购物车数量
     */
    public function update(Request $request, int $cartId): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $result = $this->cartService->updateQuantity($cartId, $validated['quantity']);

        return response()->json($result);
    }

    /**
     * 从购物车移除
     */
    public function destroy(int $cartId): JsonResponse
    {
        $this->cartService->removeFromCart($cartId);

        return response()->json([
            'success' => true,
            'message' => '已移除'
        ]);
    }

    /**
     * 清空购物车
     */
    public function clear(): JsonResponse
    {
        $this->cartService->clearCart();

        return response()->json([
            'success' => true,
            'message' => '购物车已清空'
        ]);
    }
}
```

#### 5.4 订单创建流程

```php
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderAddress;
use App\Models\OrderTotal;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * 创建订单（事务处理）
     */
    public function createOrder(int $customerId, $cartItems, array $shippingData): Order
    {
        return DB::transaction(function () use ($customerId, $cartItems, $shippingData) {
            // 1. 创建订单主记录
            $order = Order::create([
                'customer_id' => $customerId,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'currency_code' => 'CNY',
            ]);

            // 2. 创建订单商品
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $itemSubtotal = $item->price * $item->quantity;
                
                OrderProduct::create([
                    'order_id' => $order->id,
                    'sku_id' => $item->sku_id,
                    'product_name' => $item->sku->product->name,
                    'sku_name' => $item->sku->name ?? '',
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $itemSubtotal,
                ]);
                
                $subtotal += $itemSubtotal;
            }

            // 3. 创建订单收货地址
            OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'shipping',
                'first_name' => $shippingData['first_name'],
                'last_name' => $shippingData['last_name'],
                'address1' => $shippingData['address1'],
                'address2' => $shippingData['address2'] ?? '',
                'city' => $shippingData['city'],
                'state' => $shippingData['state'] ?? '',
                'country' => $shippingData['country'] ?? 'CN',
                'postcode' => $shippingData['postcode'] ?? '',
                'phone' => $shippingData['phone'] ?? '',
            ]);

            // 4. 创建订单金额汇总
            $shippingCost = $shippingData['shipping_cost'] ?? 0;
            $total = $subtotal + $shippingCost;

            OrderTotal::create([
                'order_id' => $order->id,
                'code' => 'subtotal',
                'title' => '小计',
                'value' => $subtotal,
            ]);

            OrderTotal::create([
                'order_id' => $order->id,
                'code' => 'shipping',
                'title' => '运费',
                'value' => $shippingCost,
            ]);

            OrderTotal::create([
                'order_id' => $order->id,
                'code' => 'total',
                'title' => '总计',
                'value' => $total,
            ]);

            // 5. 扣减库存
            foreach ($cartItems as $item) {
                $item->sku->decrement('stock', $item->quantity);
            }

            return $order;
        });
    }

    /**
     * 生成订单号
     */
    protected function generateOrderNumber(): string
    {
        return 'ORD' . date('YmdHis') . rand(1000, 9999);
    }
}
```

---

### 阶段六：核心功能开发 - 用户系统

**预计时间：2天**

#### 6.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 6.1.1 | 安装配置Breeze | 用户认证 |
| 6.1.2 | 客户注册登录 | 注册/登录/登出 |
| 6.1.3 | 地址管理 | 收货地址CRUD |
| 6.1.4 | 收藏夹功能 | 添加/移除收藏 |
| 6.1.5 | 个人中心 | 订单查看、资料修改 |

#### 6.2 安装Breeze认证

```bash
# 安装Breeze
php artisan breeze:install

# 选择配置：
# - 认证风格: Blade
# - 堆栈: Vite
# - 预处理器: TypeScript
# - 民族: 否

# 运行迁移
php artisan migrate

# 安装前端依赖
npm install && npm run build
```

#### 6.3 扩展客户模型

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable
{
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * 获取客户全名
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * 关联地址
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * 关联订单
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    /**
     * 关联收藏夹
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
}
```

#### 6.4 客户地址管理

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    /**
     * 获取客户地址列表
     */
    public function index(): JsonResponse
    {
        $addresses = CustomerAddress::where('customer_id', auth()->id())
            ->get();

        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }

    /**
     * 创建地址
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'company' => 'nullable|string|max:100',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|size:2',
            'postcode' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        // 如果设为默认地址，先取消其他默认
        if ($validated['is_default'] ?? false) {
            CustomerAddress::where('customer_id', auth()->id())
                ->update(['is_default' => false]);
        }

        $address = CustomerAddress::create([
            ...$validated,
            'customer_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }

    /**
     * 删除地址
     */
    public function destroy(int $id): JsonResponse
    {
        CustomerAddress::where('id', $id)
            ->where('customer_id', auth()->id())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => '地址已删除'
        ]);
    }
}
```

---

### 阶段七：后台管理系统开发

**预计时间：4天**

#### 7.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 7.1.1 | 后台脚手架 | Admin模板 |
| 7.1.2 | 管理员认证 | 登录/登出 |
| 7.1.3 | 权限系统设计 | 权限表/角色表 |
| 7.1.4 | 角色管理 | 角色CRUD |
| 7.1.5 | 管理员管理 | 用户CRUD |
| 7.1.6 | 日志模块 | 操作日志/登录日志 |
| 7.1.7 | API管理 | API文档/测试工具 |
| 7.1.8 | 商品管理 | 后台CRUD |
| 7.1.9 | 订单管理 | 订单处理 |
| 7.1.10 | 客户管理 | 客户列表 |
| 7.1.11 | 系统设置 | 配置管理 |

#### 7.2 后台目录结构

参考Bagisto的模块化设计和BeikeShop的50+控制器设计：

```
app/Http/Controllers/Admin/
├── Auth/
│   ├── LoginController.php
│   └── LogoutController.php
├── HomeController.php
├── ProductController.php
├── CategoryController.php
├── BrandController.php
├── OrderController.php
├── CustomerController.php
├── SettingController.php
└── AdminUserController.php
```

#### 7.3 后台路由

```php
<?php

// routes/admin.php
use Illuminate\Support\Facades\Route;

// 后台认证路由
Route::middleware('guest:admin')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login']);
});

// 需要认证的后台路由
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::post('logout', [LogoutController::class, 'logout'])->name('admin.logout');
    
    // Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('admin.home');
    
    // 商品管理
    Route::resource('products', ProductController::class);
    
    // 分类管理
    Route::resource('categories', CategoryController::class);
    
    // 品牌管理
    Route::resource('brands', BrandController::class);
    
    // 订单管理
    Route::resource('orders', OrderController::class);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::post('orders/{order}/ship', [OrderController::class, 'createShipment']);
    
    // 客户管理
    Route::resource('customers', CustomerController::class);
    
    // 系统设置
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('settings', [SettingController::class, 'store']);
});
```

#### 7.4 使用Spatie Permission管理权限

参考BeikeShop的后台用户管理和Bagisto的ACL系统，设计完整的权限角色体系：

```bash
# 发布Spatie Permission配置
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

##### 7.4.1 数据库设计 - 管理员相关表

```php
<?php

// database/migrations/xxxx_xx_xx_create_admin_users_table.php
public function up(): void
{
    Schema::create('admin_users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('avatar')->nullable();
        $table->foreignId('role_id')->nullable()->constrained('admin_roles');
        $table->boolean('is_active')->default(true);
        $table->timestamp('last_login_at')->nullable();
        $table->timestamps();
    });
}

// database/migrations/xxxx_xx_xx_create_admin_roles_table.php
public function up(): void
{
    Schema::create('admin_roles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('slug')->unique();
        $table->string('description')->nullable();
        $table->boolean('is_default')->default(false);
        $table->timestamps();
    });
}

// database/migrations/xxxx_xx_xx_create_admin_permissions_table.php
public function up(): void
{
    Schema::create('admin_permissions', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('slug')->unique();
        $table->string('module');  // 模块：product, order, customer, system
        $table->string('description')->nullable();
        $table->timestamps();
    });
}

// 角色-权限关联表
Schema::create('admin_role_permissions', function (Blueprint $table) {
    $table->foreignId('role_id')->constrained('admin_roles')->cascadeOnDelete();
    $table->foreignId('permission_id')->constrained('admin_permissions')->cascadeOnDelete();
    $table->primary(['role_id', 'permission_id']);
});
```

##### 7.4.2 管理员模型

```php
<?php

// app/Models/AdminUser.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends Authenticatable
{
    use HasRoles;  // 使用Spatie的HasRoles trait

    protected $table = 'admin_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role_id',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * 判断用户是否超级管理员
     */
    public function isSuperAdmin(): bool
    {
        return $this->email === config('app.super_admin_email');
    }

    /**
     * 检查是否有某权限
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->hasPermissionTo($permission);
    }
}
```

##### 7.4.3 权限种子数据

```php
<?php

// database/seeders/AdminPermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminPermission;

class AdminPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // 商品模块
            ['name' => '商品列表', 'slug' => 'product.list', 'module' => 'product'],
            ['name' => '创建商品', 'slug' => 'product.create', 'module' => 'product'],
            ['name' => '编辑商品', 'slug' => 'product.edit', 'module' => 'product'],
            ['name' => '删除商品', 'slug' => 'product.delete', 'module' => 'product'],
            
            // 分类模块
            ['name' => '分类管理', 'slug' => 'category.manage', 'module' => 'category'],
            
            // 品牌模块
            ['name' => '品牌管理', 'slug' => 'brand.manage', 'module' => 'brand'],
            
            // 订单模块
            ['name' => '订单列表', 'slug' => 'order.list', 'module' => 'order'],
            ['name' => '处理订单', 'slug' => 'order.process', 'module' => 'order'],
            ['name' => '取消订单', 'slug' => 'order.cancel', 'module' => 'order'],
            
            // 客户模块
            ['name' => '客户列表', 'slug' => 'customer.list', 'module' => 'customer'],
            ['name' => '管理客户', 'slug' => 'customer.manage', 'module' => 'customer'],
            
            // 系统模块
            ['name' => '管理员管理', 'slug' => 'admin.manage', 'module' => 'system'],
            ['name' => '角色管理', 'slug' => 'role.manage', 'module' => 'system'],
            ['name' => '权限管理', 'slug' => 'permission.manage', 'module' => 'system'],
            ['name' => '系统设置', 'slug' => 'settings.manage', 'module' => 'system'],
        ];

        foreach ($permissions as $permission) {
            AdminPermission::create($permission);
        }
    }
}
```

##### 7.4.4 角色管理控制器

```php
<?php

// app/Http/Controllers/Admin/RoleController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * 角色列表
     */
    public function index()
    {
        $roles = AdminRole::withCount('permissions')->paginate(20);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * 创建角色页面
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * 保存角色
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:admin_roles',
            'slug' => 'required|string|max:50|unique:admin_roles',
            'description' => 'nullable|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = AdminRole::create($validated);
        
        if (!empty($validated['permissions'])) {
            $role->givePermissionTo($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', '角色创建成功');
    }

    /**
     * 编辑角色页面
     */
    public function edit(AdminRole $role)
    {
        $permissions = Permission::all()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * 更新角色
     */
    public function update(Request $request, AdminRole $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:admin_roles,name,' . $role->id,
            'slug' => 'required|string|max:50|unique:admin_roles,slug,' . $role->id,
            'description' => 'nullable|string|max:255',
            'permissions' => 'array',
        ]);

        $role->update($validated);
        
        // 同步权限
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', '角色更新成功');
    }

    /**
     * 删除角色
     */
    public function destroy(AdminRole $role)
    {
        if ($role->is_default) {
            return back()->with('error', '默认角色不能删除');
        }

        $role->delete();
        return back()->with('success', '角色删除成功');
    }
}
```

##### 7.4.5 管理员用户管理控制器

```php
<?php

// app/Http/Controllers/Admin/AdminUserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * 管理员列表
     */
    public function index(Request $request)
    {
        $users = AdminUser::with('role')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->when($request->role_id, function ($query) use ($request) {
                $query->where('role_id', $request->role_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * 创建管理员页面
     */
    public function create()
    {
        $roles = AdminRole::where('is_active', true)->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * 保存管理员
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:admin_users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'nullable|exists:admin_roles,id',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        AdminUser::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', '管理员创建成功');
    }

    /**
     * 编辑管理员页面
     */
    public function edit(AdminUser $user)
    {
        $roles = AdminRole::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * 更新管理员
     */
    public function update(Request $request, AdminUser $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:admin_users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'nullable|exists:admin_roles,id',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', '管理员更新成功');
    }

    /**
     * 删除管理员
     */
    public function destroy(AdminUser $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', '不能删除自己的账号');
        }

        $user->delete();
        return back()->with('success', '管理员删除成功');
    }
}
```

##### 7.4.6 路由配置

```php
<?php

// routes/admin.php 新增
Route::middleware('auth:admin')->group(function () {
    // ... 其他路由
    
    // 角色管理
    Route::resource('roles', RoleController::class);
    
    // 管理员用户管理
    Route::resource('users', AdminUserController::class);
    Route::put('users/{user}/status', [AdminUserController::class, 'toggleStatus']);
});
```

##### 7.4.7 权限中间件

```php
<?php

// app/Http/Middleware/CheckAdminPermission.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth('admin')->user();

        // 超级管理员直接通过
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // 检查权限
        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => '您没有权限执行此操作'
                ], 403);
            }
            
            return redirect()->back()->with('error', '您没有权限执行此操作');
        }

        return $next($request);
    }
}
```

注册中间件后在路由中使用：

```php
Route::middleware('auth:admin,permission:product.create')->group(function () {
    Route::post('products', [ProductController::class, 'store']);
});
```

#### 7.6 日志模块

参考Aimeos的MAdmin日志管理和BeikeShop的设计日志，实现完整的日志记录系统：

##### 7.6.1 日志类型与数据库设计

```php
<?php

// database/migrations/xxxx_xx_xx_create_admin_logs_table.php
public function up(): void
{
    Schema::create('admin_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('admin_user_id')->nullable()->constrained('admin_users')->nullOnDelete();
        $table->string('module');        // 模块：product, order, system
        $table->string('action');        // 操作：create, update, delete, login, logout
        $table->string('title');         // 日志标题
        $table->text('description')->nullable(); // 详细描述
        $table->json('old_data')->nullable();   // 修改前的数据
        $table->json('new_data')->nullable();   // 修改后的数据
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->timestamps();

        $table->index('module');
        $table->index('action');
        $table->index('created_at');
    });
}

// database/migrations/xxxx_xx_xx_create_login_logs_table.php
public function up(): void
{
    Schema::create('login_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('admin_user_id')->nullable()->constrained('admin_users')->nullOnDelete();
        $table->string('email')->nullable();
        $table->string('action');        // login, logout, failed
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->boolean('is_success')->default(true);
        $table->timestamp('created_at');

        $table->index('created_at');
    });
}
```

##### 7.6.2 日志模型

```php
<?php

// app/Models/AdminLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    protected $table = 'admin_logs';

    protected $fillable = [
        'admin_user_id',
        'module',
        'action',
        'title',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    /**
     * 关联管理员
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

    /**
     * 记录操作日志
     */
    public static function log(
        string $module,
        string $action,
        string $title,
        ?string $description = null,
        ?array $oldData = null,
        ?array $newData = null
    ): self {
        $user = auth('admin')->user();

        return self::create([
            'admin_user_id' => $user?->id,
            'module' => $module,
            'action' => $action,
            'title' => $title,
            'description' => $description,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}

// app/Models/LoginLog.php
class LoginLog extends Model
{
    protected $table = 'login_logs';

    protected $fillable = [
        'admin_user_id',
        'email',
        'action',
        'ip_address',
        'user_agent',
        'is_success',
    ];

    protected $casts = [
        'is_success' => 'boolean',
        'created_at' => 'datetime',
    ];
}
```

##### 7.6.3 日志记录服务

```php
<?php

// app/Services/AdminLogService.php
namespace App\Services;

use App\Models\AdminLog;
use App\Models\LoginLog;

class AdminLogService
{
    /**
     * 记录操作日志
     */
    public static function log(
        string $module,
        string $action,
        string $title,
        ?string $description = null,
        ?array $oldData = null,
        ?array $newData = null
    ): void {
        AdminLog::log($module, $action, $title, $description, $oldData, $newData);
    }

    /**
     * 记录登录日志
     */
    public static function logLogin(?int $userId, string $email, bool $success, string $action = 'login'): void
    {
        LoginLog::create([
            'admin_user_id' => $userId,
            'email' => $email,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_success' => $success,
        ]);
    }

    /**
     * 记录创建操作
     */
    public static function logCreate(string $module, string $title, array $data): void
    {
        self::log($module, 'create', $title, '创建记录', null, $data);
    }

    /**
     * 记录更新操作
     */
    public static function logUpdate(string $module, string $title, array $oldData, array $newData): void
    {
        self::log($module, 'update', $title, '更新记录', $oldData, $newData);
    }

    /**
     * 记录删除操作
     */
    public static function logDelete(string $module, string $title, array $data): void
    {
        self::log($module, 'delete', $title, '删除记录', $data, null);
    }
}
```

##### 7.6.4 日志管理控制器

```php
<?php

// app/Http/Controllers/Admin/LogController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\LoginLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * 操作日志列表
     */
    public function operationIndex(Request $request)
    {
        $logs = AdminLog::with('adminUser')
            ->when($request->module, function ($query) use ($request) {
                $query->where('module', $request->module);
            })
            ->when($request->action, function ($query) use ($request) {
                $query->where('action', $request->action);
            })
            ->when($request->admin_user_id, function ($query) use ($request) {
                $query->where('admin_user_id', $request->admin_user_id);
            })
            ->when($request->date_range, function ($query) use ($request) {
                $dates = explode(' - ', $request->date_range);
                if (count($dates) === 2) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($dates[0])->startOfDay(),
                        Carbon::parse($dates[1])->endOfDay(),
                    ]);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.logs.operation', compact('logs'));
    }

    /**
     * 登录日志列表
     */
    public function loginIndex(Request $request)
    {
        $logs = LoginLog::with('adminUser')
            ->when($request->action, function ($query) use ($request) {
                $query->where('action', $request->action);
            })
            ->when($request->is_success !== null, function ($query) use ($request) {
                $query->where('is_success', $request->is_success);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.logs.login', compact('logs'));
    }

    /**
     * 日志详情
     */
    public function show(AdminLog $log)
    {
        return view('admin.logs.show', compact('log'));
    }

    /**
     * 清空日志
     */
    public function clear(Request $request)
    {
        $type = $request->input('type', 'operation');
        
        if ($type === 'operation') {
            AdminLog::truncate();
        } else {
            LoginLog::truncate();
        }

        return back()->with('success', '日志已清空');
    }
}
```

##### 7.6.5 在业务中使用日志

```php
<?php

// 在控制器中使用示例
class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        // 记录日志
        AdminLogService::logCreate('product', '创建商品: ' . $product->name, $product->toArray());

        return redirect()->route('admin.products.index')
            ->with('success', '产品创建成功');
    }

    public function update(Request $request, Product $product)
    {
        $oldData = $product->toArray();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        // 记录日志
        AdminLogService::logUpdate(
            'product', 
            '更新商品: ' . $product->name, 
            $oldData, 
            $product->fresh()->toArray()
        );

        return redirect()->route('admin.products.index')
            ->with('success', '产品更新成功');
    }

    public function destroy(Product $product)
    {
        $productData = $product->toArray();
        $productName = $product->name;
        
        $product->delete();

        // 记录日志
        AdminLogService::logDelete('product', '删除商品: ' . $productName, $productData);

        return back()->with('success', '产品删除成功');
    }
}
```

##### 7.6.6 日志路由配置

```php
// routes/admin.php
Route::middleware('auth:admin')->group(function () {
    // ... 其他路由
    
    // 日志管理
    Route::get('logs/operation', [LogController::class, 'operationIndex'])->name('admin.logs.operation');
    Route::get('logs/login', [LogController::class, 'loginIndex'])->name('admin.logs.login');
    Route::get('logs/{log}', [LogController::class, 'show'])->name('admin.logs.show');
    Route::delete('logs/clear', [LogController::class, 'clear'])->name('admin.logs.clear');
});
```

#### 7.7 API管理与测试模块

参考Aimeos的JSON API和BeikeShop的AdminAPI设计，实现完整的API管理系统：

##### 7.7.1 数据库设计 - API文档表

```php
<?php

// database/migrations/xxxx_xx_xx_create_api_docs_table.php
public function up(): void
{
    Schema::create('api_docs', function (Blueprint $table) {
        $table->id();
        $table->string('name');              // 接口名称
        $table->string('group');             // 分组：product, order, customer
        $table->string('method');             // GET, POST, PUT, DELETE
        $table->string('path');               // 接口路径
        $table->text('description')->nullable(); // 描述
        $table->json('parameters')->nullable;    // 请求参数
        $table->json('responses')->nullable;    // 响应示例
        $table->boolean('is_auth')->default(false); // 是否需要认证
        $table->string('middleware')->nullable;   // 中间件
        $table->integer('sort')->default(0);      // 排序
        $table->boolean('is_active')->default(true);
        $table->timestamps();

        $table->index('group');
        $table->index('is_active');
    });
}

// database/migrations/xxxx_xx_xx_create_api_tests_table.php
public function up(): void
{
    Schema::create('api_tests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('api_doc_id')->constrained('api_docs')->cascadeOnDelete();
        $table->foreignId('admin_user_id')->constrained('admin_users');
        $table->string('name');               // 测试名称
        $table->string('url');                // 请求URL
        $table->string('method');             // 请求方法
        $table->json('headers')->nullable();  // 请求头
        $table->json('body')->nullable();     // 请求体
        $table->integer('status_code')->nullable(); // 响应状态码
        $table->text('response')->nullable(); // 响应内容
        $table->integer('response_time')->nullable(); // 响应时间(ms)
        $table->timestamps();

        $table->index('api_doc_id');
    });
}
```

##### 7.7.2 API文档模型

```php
<?php

// app/Models/ApiDoc.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiDoc extends Model
{
    protected $table = 'api_docs';

    protected $fillable = [
        'name',
        'group',
        'method',
        'path',
        'description',
        'parameters',
        'responses',
        'is_auth',
        'middleware',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'parameters' => 'array',
        'responses' => 'array',
        'is_auth' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * 关联测试记录
     */
    public function tests(): HasMany
    {
        return $this->hasMany(ApiTest::class, 'api_doc_id');
    }

    /**
     * 获取方法颜色
     */
    public function getMethodColorAttribute(): string
    {
        return match($this->method) {
            'GET' => 'green',
            'POST' => 'blue',
            'PUT', 'PATCH' => 'orange',
            'DELETE' => 'red',
            default => 'gray',
        };
    }
}
```

##### 7.7.3 API管理控制器

```php
<?php

// app/Http/Controllers/Admin/ApiDocController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiDoc;
use Illuminate\Http\Request;

class ApiDocController extends Controller
{
    /**
     * API文档列表
     */
    public function index(Request $request)
    {
        $docs = ApiDoc::when($request->group, function ($query) use ($request) {
                $query->where('group', $request->group);
            })
            ->when($request->method, function ($query) use ($request) {
                $query->where('method', $request->method);
            })
            ->orderBy('group')
            ->orderBy('sort')
            ->paginate(50);

        $groups = ApiDoc::distinct()->pluck('group');

        return view('admin.api.index', compact('docs', 'groups'));
    }

    /**
     * 创建API文档页面
     */
    public function create()
    {
        return view('admin.api.create');
    }

    /**
     * 保存API文档
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'group' => 'required|string|max:50',
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'path' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parameters' => 'nullable|array',
            'responses' => 'nullable|array',
            'is_auth' => 'boolean',
            'middleware' => 'nullable|string|max:100',
        ]);

        ApiDoc::create($validated);

        return redirect()->route('admin.api.index')
            ->with('success', 'API文档创建成功');
    }

    /**
     * 编辑API文档页面
     */
    public function edit(ApiDoc $apiDoc)
    {
        return view('admin.api.edit', compact('apiDoc'));
    }

    /**
     * 更新API文档
     */
    public function update(Request $request, ApiDoc $apiDoc)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'group' => 'required|string|max:50',
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'path' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parameters' => 'nullable|array',
            'responses' => 'nullable|array',
            'is_auth' => 'boolean',
            'middleware' => 'nullable|string|max:100',
        ]);

        $apiDoc->update($validated);

        return redirect()->route('admin.api.index')
            ->with('success', 'API文档更新成功');
    }

    /**
     * 删除API文档
     */
    public function destroy(ApiDoc $apiDoc)
    {
        $apiDoc->delete();
        return back()->with('success', 'API文档删除成功');
    }
}
```

##### 7.7.4 API测试控制器

```php
<?php

// app/Http/Controllers/Admin/ApiTestController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiDoc;
use App\Models\ApiTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ApiTestController extends Controller
{
    /**
     * API测试页面
     */
    public function index(ApiDoc $apiDoc)
    {
        $tests = $apiDoc->tests()
            ->with('adminUser')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.api.test', compact('apiDoc', 'tests'));
    }

    /**
     * 执行API测试
     */
    public function test(Request $request, ApiDoc $apiDoc)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'headers' => 'nullable|array',
            'body' => 'nullable|array',
        ]);

        $startTime = microtime(true);

        try {
            $response = Http::timeout(30)
                ->withHeaders($validated['headers'] ?? [])
                ->{$validated['method']}($validated['url'], $validated['body'] ?? []);

            $responseTime = (microtime(true) - $startTime) * 1000;
            $statusCode = $response->status();
            $responseBody = $response->body();

            // 记录测试结果
            $test = ApiTest::create([
                'api_doc_id' => $apiDoc->id,
                'admin_user_id' => Auth::id(),
                'name' => '手动测试',
                'url' => $validated['url'],
                'method' => $validated['method'],
                'headers' => $validated['headers'],
                'body' => $validated['body'],
                'status_code' => $statusCode,
                'response' => $responseBody,
                'response_time' => $responseTime,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'status_code' => $statusCode,
                    'response_time' => round($responseTime, 2),
                    'response' => json_decode($responseBody) ?: $responseBody,
                    'test_id' => $test->id,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 测试历史
     */
    public function history(Request $request)
    {
        $tests = ApiTest::with(['apiDoc', 'adminUser'])
            ->when($request->api_doc_id, function ($query) use ($request) {
                $query->where('api_doc_id', $request->api_doc_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.api.history', compact('tests'));
    }
}
```

##### 7.7.5 自动生成API文档（Laravel Scribe）

```bash
# 安装 Scribe API文档生成器
composer require --dev knuckleswtf/scribe

# 发布配置
php artisan vendor:publish --provider="Knuckles\Scribe\ScribeServiceProvider"
```

```yaml
# config/scribe.php 配置示例
return [
    'title' => 'NewShop API',
    'description' => 'NewShop 商城 API 文档',
    'base_url' => env('APP_URL'),
    
    'routes' => [
        [
            'match' => [
                'prefixes' => ['api/'],
            ],
        ],
    ],

    'auth' => [
        'enabled' => true,
        'in' => 'bearer',
        'name' => 'Authorization',
    ],

    'postman' => [
        'enabled' => true,
    ],

    'openapi' => [
        'enabled' => true,
    ],
];
```

```bash
# 生成API文档
php artisan scribe:generate
```

##### 7.7.6 API路由配置

```php
// routes/admin.php
Route::middleware('auth:admin')->group(function () {
    // ... 其他路由
    
    // API文档管理
    Route::resource('api', ApiDocController::class);
    
    // API测试
    Route::get('api/test/{apiDoc}', [ApiTestController::class, 'index'])->name('api.test');
    Route::post('api/test/{apiDoc}', [ApiTestController::class, 'test'])->name('api.test.run');
    Route::get('api/history', [ApiTestController::class, 'history'])->name('api.history');
});

// 公开的API文档页面
Route::get('/api/docs', function () {
    return redirect('/docs/index.html');
});
```

##### 7.7.7 API测试示例（使用Postman/Insomnia）

```
# API基础URL
http://localhost:8000/api

# 公共接口
GET    /products                  # 产品列表
GET    /products/{id}            # 产品详情
GET    /categories                # 分类列表
GET    /categories/{id}          # 分类详情

# 需要认证的接口
POST   /cart                      # 加入购物车
GET    /cart                      # 购物车列表
POST   /checkout                  # 创建订单

# 认证接口
POST   /auth/register             # 用户注册
POST   /auth/login                # 用户登录
POST   /auth/logout               # 登出

# 请求头示例
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}

# 响应格式
{
    "success": true,
    "data": {},
    "message": "操作成功"
}

# 错误响应
{
    "success": false,
    "message": "错误信息",
    "errors": {}
}
```

##### 7.7.8 Laravel API资源（API格式化）

```php
<?php

// app/Http/Resources/ProductResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => (float) $this->price,
            'original_price' => $this->original_price ? (float) $this->original_price : null,
            'stock' => $this->stock,
            'sales_count' => $this->sales_count,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'image' => $this->image?->url,
            'category' => $this->category?->name,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

// app/Http/Resources/ProductCollection.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public $collects = ProductResource::class;

    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ],
        ];
    }
}
```

使用示例：

```php
// app/Http/Controllers/Api/ProductController.php
public function index(Request $request)
{
    $products = Product::with(['category', 'image'])
        ->where('is_active', true)
        ->paginate($request->input('per_page', 15));

    return new ProductCollection($products);
}

public function show(int $id)
{
    $product = Product::with(['category', 'skus', 'images'])
        ->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => new ProductResource($product),
    ]);
}
```

#### 7.5 后台产品管理示例

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 产品列表
     */
    public function index(Request $request)
    {
        $products = Product::with(['category'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%");
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    /**
     * 创建产品页面
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * 保存产品
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', '产品创建成功');
    }

    /**
     * 编辑产品页面
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * 更新产品
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', '产品更新成功');
    }

    /**
     * 删除产品
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['success' => true]);
    }
}
```

---

### 阶段八：高级功能 - 发票/退款/优惠券

**预计时间：2天**

#### 8.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 8.1.1 | 发票管理 | 发票生成/查看/下载 |
| 8.1.2 | 退款管理 | 退款申请/审核/处理 |
| 8.1.3 | 优惠券系统 | 优惠码/折扣/使用限制 |
| 8.1.4 | 促销规则 | 购物车规则/产品规则 |

#### 8.2 发票管理

参考Bagisto的Invoice设计：

```php
<?php

// database/migrations/xxxx_xx_xx_create_invoices_table.php
public function up(): void
{
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
        $table->string('customer_email');
        $table->string('customer_name');
        $table->string('customer_address');
        $table->decimal('subtotal', 12, 2);
        $table->decimal('tax_amount', 12, 2)->default(0);
        $table->decimal('discount_amount', 12, 2)->default(0);
        $table->decimal('total', 12, 2);
        $table->string('status')->default('pending'); // pending, paid, cancelled
        $table->timestamp('invoice_date');
        $table->timestamps();

        $table->index('invoice_number');
        $table->index('status');
    });
}
```

```php
<?php

// app/Models/Invoice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'order_id',
        'customer_email',
        'customer_name',
        'customer_address',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total',
        'status',
        'invoice_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'invoice_date' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 生成发票号
     */
    public static function generateInvoiceNumber(): string
    {
        return 'INV' . date('Ymd') . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}
```

```php
<?php

// app/Services/InvoiceService.php
namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * 为订单生成发票
     */
    public function createInvoice(Order $order): Invoice
    {
        return DB::transaction(function () use ($order) {
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'order_id' => $order->id,
                'customer_email' => $order->customer->email,
                'customer_name' => $order->customer->full_name,
                'customer_address' => $order->shippingAddress?->full_address,
                'subtotal' => $order->getSubtotal(),
                'tax_amount' => $order->getTaxAmount(),
                'discount_amount' => $order->getDiscountAmount(),
                'total' => $order->getTotal(),
                'status' => 'pending',
                'invoice_date' => now(),
            ]);

            $order->update(['invoice_id' => $invoice->id]);

            return $invoice;
        });
    }

    /**
     * 生成PDF发票
     */
    public function generatePdf(Invoice $invoice)
    {
        $pdf = \PDF::loadView('invoices.template', [
            'invoice' => $invoice,
            'order' => $invoice->order,
        ]);

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}
```

#### 8.3 退款管理

```php
<?php

// database/migrations/xxxx_xx_xx_create_refunds_table.php
public function up(): void
{
    Schema::create('refunds', function (Blueprint $table) {
        $table->id();
        $table->string('refund_number')->unique();
        $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
        $table->foreignId('customer_id')->constrained('customers');
        $table->decimal('amount', 12, 2);
        $table->string('reason')->nullable();
        $table->text('note')->nullable();
        $table->string('status')->default('pending'); // pending, approved, rejected, processed
        $table->string('payment_method')->nullable();
        $table->timestamp('processed_at')->nullable();
        $table->timestamps();

        $table->index('refund_number');
        $table->index('status');
    });
}
```

```php
<?php

// app/Http/Controllers/Admin/RefundController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $refunds = Refund::with(['order', 'customer'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function approve(Refund $refund)
    {
        $refund->update([
            'status' => 'approved',
        ]);

        return back()->with('success', '退款已批准');
    }

    public function reject(Request $request, Refund $refund)
    {
        $refund->update([
            'status' => 'rejected',
            'note' => $request->note,
        ]);

        return back()->with('success', '退款已拒绝');
    }

    public function process(Refund $refund)
    {
        // TODO: 调用支付网关退款
        $refund->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        // 恢复库存
        foreach ($refund->order->items as $item) {
            $item->sku->increment('stock', $item->quantity);
        }

        return back()->with('success', '退款已处理');
    }
}
```

#### 8.4 优惠券系统

```php
<?php

// database/migrations/xxxx_xx_xx_create_coupons_table.php
public function up(): void
{
    Schema::create('coupons', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();  // 优惠码
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('type');  // fixed, percentage
        $table->decimal('value', 10, 2);  // 优惠值
        $table->decimal('min_order_amount', 10, 2)->nullable();  // 最低订单金额
        $table->decimal('max_discount_amount', 10, 2)->nullable();  // 最高优惠金额
        $table->integer('usage_limit')->nullable();  // 总使用次数限制
        $table->integer('usage_per_customer')->default(1);  // 每个客户使用次数
        $table->integer('usage_count')->default(0);  // 已使用次数
        $table->timestamp('starts_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();

        $table->index('code');
        $table->index('is_active');
    });
}

Schema::create('coupon_usage', function (Blueprint $table) {
    $table->id();
    $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
    $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
    $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
    $table->integer('usage_count')->default(1);
    $table->timestamps();
});
```

```php
<?php

// app/Services/CouponService.php
namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;

class CouponService
{
    /**
     * 验证优惠券
     */
    public function validate(string $code, int $customerId, float $orderAmount): array
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => '优惠券不存在'];
        }

        if (!$coupon->is_active) {
            return ['valid' => false, 'message' => '优惠券已禁用'];
        }

        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            return ['valid' => false, 'message' => '优惠券尚未开始'];
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return ['valid' => false, 'message' => '优惠券已过期'];
        }

        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            return ['valid' => false, 'message' => '优惠券已使用完毕'];
        }

        $customerUsage = CouponUsage::where('coupon_id', $coupon->id)
            ->where('customer_id', $customerId)
            ->sum('usage_count');

        if ($customerUsage >= $coupon->usage_per_customer) {
            return ['valid' => false, 'message' => '您已达到该优惠券使用上限'];
        }

        if ($coupon->min_order_amount && $orderAmount < $coupon->min_order_amount) {
            return ['valid' => false, 'message' => '订单金额未达到使用门槛'];
        }

        return ['valid' => true, 'coupon' => $coupon];
    }

    /**
     * 计算优惠金额
     */
    public function calculateDiscount(Coupon $coupon, float $orderAmount): float
    {
        if ($coupon->type === 'fixed') {
            return min($coupon->value, $orderAmount);
        }

        $discount = $orderAmount * ($coupon->value / 100);

        if ($coupon->max_discount_amount) {
            $discount = min($discount, $coupon->max_discount_amount);
        }

        return $discount;
    }

    /**
     * 使用优惠券
     */
    public function useCoupon(Coupon $coupon, int $customerId, int $orderId): void
    {
        $coupon->increment('usage_count');

        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'customer_id' => $customerId,
            'order_id' => $orderId,
        ]);
    }
}
```

#### 8.5 促销规则（购物车规则/产品规则）

```php
<?php

// database/migrations/xxxx_xx_xx_create_cart_rules_table.php
public function up(): void
{
    Schema::create('cart_rules', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('code')->unique()->nullable();
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        
        // 条件
        $table->json('conditions')->nullable();  // 满足条件才触发
        
        // 动作
        $table->string('action_type');  // discount_fixed, discount_percent, gift
        $table->decimal('discount_value', 10, 2)->nullable();
        $table->foreignId('gift_product_id')->nullable()->constrained('products');
        
        // 限制
        $table->integer('usage_limit')->nullable();
        $table->integer('usage_per_customer')->nullable();
        $table->timestamp('starts_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });
}
```

---

### 阶段九：高级功能 - 多语言/多货币/多站点

**预计时间：2天**

#### 9.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 9.1.1 | 多语言系统 | 语言切换/翻译 |
| 9.1.2 | 多货币系统 | 货币切换/汇率 |
| 9.1.3 | 多站点系统 | 站点管理/域名绑定 |
| 9.1.4 | 本地化配置 | 地区/税费 |

#### 9.2 多语言系统

参考Bagisto的20+语言实现：

```php
<?php

// database/migrations/xxxx_xx_xx_create_languages_table.php
public function up(): void
{
    Schema::create('languages', function (Blueprint $table) {
        $table->id();
        $table->string('code', 10)->unique();  // zh_CN, en, ja
        $table->string('name');  // 简体中文, English, 日本語
        $table->string('native_name');  // 本地化名称
        $table->string('direction')->default('ltr');  // ltr, rtl
        $table->boolean('is_active')->default(true);
        $table->boolean('is_default')->default(false);
        $table->integer('sort_order')->default(0);
        $table->timestamps();

        $table->index('is_active');
    });
}
```

```php
<?php

// app/Services/LocalizationService.php
namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class LocalizationService
{
    /**
     * 获取当前语言
     */
    public function getCurrentLocale(): string
    {
        return session('locale', config('app.locale'));
    }

    /**
     * 设置语言
     */
    public function setLocale(string $locale): void
    {
        if (!Language::where('code', $locale)->where('is_active', true)->exists()) {
            return;
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    /**
     * 获取所有可用语言
     */
    public function getAvailableLocales(): array
    {
        return Cache::remember('available_locales', 3600, function () {
            return Language::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->toArray();
        });
    }

    /**
     * 翻译关键词
     */
    public function translate(string $key, ?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        
        $translations = Cache::remember("translations_{$locale}", 3600, function () use ($locale) {
            return Translation::where('locale', $locale)
                ->pluck('value', 'key')
                ->toArray();
        });

        return $translations[$key] ?? $key;
    }
}
```

```php
<?php

// routes/web.php - 语言切换
Route::get('/locale/{code}', function ($code) {
    app(LocalizationService::class)->setLocale($code);
    return back();
})->name('locale.switch');
```

#### 9.3 多货币系统

```php
<?php

// database/migrations/xxxx_xx_xx_create_currencies_table.php
public function up(): void
{
    Schema::create('currencies', function (Blueprint $table) {
        $table->id();
        $table->string('code', 3)->unique();  // CNY, USD, EUR
        $table->string('name');
        $table->string('symbol');  // ¥, $, €
        $table->string('format');  // {symbol}{price}
        $table->decimal('exchange_rate', 10, 6)->default(1);  // 相对基准货币汇率
        $table->boolean('is_active')->default(true);
        $table->boolean('is_default')->default(false);
        $table->integer('sort_order')->default(0);
        $table->timestamps();

        $table->index('is_active');
    });
}
```

```php
<?php

// app/Services/CurrencyService.php
namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    protected ?Currency $currentCurrency = null;

    /**
     * 获取当前货币
     */
    public function getCurrentCurrency(): Currency
    {
        if ($this->currentCurrency) {
            return $this->currentCurrency;
        }

        $currencyCode = session('currency', config('app.currency'));

        $this->currentCurrency = Currency::where('code', $currencyCode)
            ->where('is_active', true)
            ->firstOrFail();

        return $this->currentCurrency;
    }

    /**
     * 转换价格
     */
    public function convert(float $amount, ?string $from = null, ?string $to = null): float
    {
        $fromCurrency = $from 
            ? Currency::where('code', $from)->firstOrFail()
            : $this->getCurrentCurrency();
            
        $toCurrency = $to 
            ? Currency::where('code', $to)->firstOrFail()
            : $this->getCurrentCurrency();

        // 转换为基准货币，再转换为目标货币
        $baseAmount = $amount / $fromCurrency->exchange_rate;
        return $baseAmount * $toCurrency->exchange_rate;
    }

    /**
     * 格式化价格
     */
    public function format(float $amount, ?string $currencyCode = null): string
    {
        $currency = $currencyCode 
            ? Currency::where('code', $currencyCode)->firstOrFail()
            : $this->getCurrentCurrency();

        $format = $currency->format;
        $price = number_format($amount, 2, '.', ',');
        
        return str_replace('{symbol}', $currency->symbol, str_replace('{price}', $price, $format));
    }
}
```

#### 9.4 多站点系统

参考Aimeos的多站点设计：

```php
<?php

// database/migrations/xxxx_xx_xx_create_channels_table.php
public function up(): void
{
    Schema::create('channels', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();  // main, store1, store2
        $table->string('name');
        $table->string('domain')->nullable();  // 域名
        $table->string('default_locale')->default('zh_CN');
        $table->string('default_currency')->default('CNY');
        $table->text('logo')->nullable();
        $table->text('favicon')->nullable();
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->boolean('is_default')->default(false);
        $table->timestamps();

        $table->index('is_active');
    });
}

// 站点ID中间件
public function handle($request, Closure $next)
{
    $host = $request->getHost();
    
    $channel = Channel::where('domain', $host)
        ->orWhere('code', 'main')
        ->first();
    
    if (!$channel || !$channel->is_active) {
        abort(404);
    }

    config(['app.channel' => $channel]);
    session(['channel_id' => $channel->id]);

    return $next($request);
}
```

---

### 阶段十：高级功能 - CMS/页面装修/SEO

**预计时间：2天**

#### 10.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 10.1.1 | CMS文章系统 | 文章/分类管理 |
| 10.1.2 | 页面装修 | 可视化拖拽装修 |
| 10.1.3 | SEO设置 | Meta/URL优化 |
| 10.1.4 | 地区管理 | 省市区/税区 |

#### 10.2 CMS文章系统

参考BeikeShop的Page设计：

```php
<?php

// database/migrations/xxxx_xx_xx_create_pages_table.php
public function up(): void
{
    Schema::create('pages', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('content')->nullable();
        $table->text('content_html')->nullable();
        $table->string('meta_title')->nullable();
        $table->text('meta_description')->nullable();
        $table->string('meta_keywords')->nullable();
        $table->string('featured_image')->nullable();
        $table->foreignId('category_id')->nullable()->constrained('page_categories');
        $table->integer('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        $table->boolean('is_featured')->default(false);
        $table->timestamps();

        $table->index('slug');
        $table->index('is_active');
    });
}

Schema::create('page_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

```php
<?php

// app/Http/Controllers/PageController.php
class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::where('is_active', true)
            ->orderBy('sort_order')
            ->paginate(20);

        return view('pages.index', compact('pages'));
    }

    public function show(Page $page)
    {
        if (!$page->is_active) {
            abort(404);
        }

        return view('pages.show', compact('page'));
    }
}
```

#### 10.3 页面装修系统

参考BeikeShop的可视化装修：

```php
<?php

// database/migrations/xxxx_xx_xx_create_theme_settings_table.php
public function up(): void
{
    Schema::create('theme_settings', function (Blueprint $table) {
        $table->id();
        $table->string('theme_code')->default('default');
        $table->string('section');  // header, footer, home_slider
        $table->string('element');  // 模块类型
        $table->json('settings')->nullable();  // 模块配置
        $table->integer('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();

        $table->index(['theme_code', 'section']);
    });
}
```

```php
<?php

// app/Services/ThemeService.php
namespace App\Services;

use App\Models\ThemeSetting;

class ThemeService
{
    /**
     * 获取页面模块
     */
    public function getSection(string $section, string $theme = 'default'): array
    {
        return ThemeSetting::where('theme_code', $theme)
            ->where('section', $section)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    /**
     * 保存页面布局
     */
    public function saveSection(string $section, array $elements): void
    {
        foreach ($elements as $index => $element) {
            ThemeSetting::updateOrCreate(
                [
                    'theme_code' => $element['theme_code'] ?? 'default',
                    'section' => $section,
                    'element' => $element['type'],
                ],
                [
                    'settings' => $element['settings'],
                    'sort_order' => $index,
                    'is_active' => true,
                ]
            );
        }
    }
}
```

装修模块类型：
- `slideshow` - 轮播图
- `product_grid` - 产品网格
- `product_carousel` - 产品轮播
- `category_banner` - 分类横幅
- `rich_text` - 富文本
- `image_banner` - 图片广告
- `tabs` - 标签页

#### 10.4 SEO优化

```php
<?php

// app/Models/Traits/Seoable.php
trait Seoable
{
    public function getMetaTitleAttribute(): string
    {
        return $this->meta_title ?? $this->title;
    }

    public function getMetaDescriptionAttribute(): string
    {
        return $this->meta_description ?? \Str::limit(strip_tags($this->description ?? ''), 160);
    }

    public function getMetaKeywordsAttribute(): string
    {
        return $this->meta_keywords ?? '';
    }

    public function getSeoUrlAttribute(): string
    {
        return url($this->slug);
    }
}

// app/Models/Product.php
use Seoable;

class Product extends Model
{
    use Seoable;
}

// 路由绑定slug
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
```

URL别名系统：

```php
<?php

// database/migrations/xxxx_xx_xx_create_url_aliases_table.php
Schema::create('url_aliases', function (Blueprint $table) {
    $table->id();
    $table->string('alias');  // /products/iphone-15
    $table->string('model_type');  // App\Models\Product
    $table->unsignedBigInteger('model_id');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique('alias');
    $table->index(['model_type', 'model_id']);
});
```

---

### 阶段十一：高级功能 - 数据导入导出/订阅

**预计时间：1天**

#### 11.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 11.1.1 | 数据导入 | Excel/CSV导入 |
| 11.1.2 | 数据导出 | Excel/CSV导出 |
| 11.1.3 | 订阅功能 | 周期性订阅 |
| 11.1.4 | 库存管理 | 多仓库库存 |

#### 11.2 数据导入导出

参考Bagisto的DataTransfer：

```bash
# 安装依赖
composer require phpoffice/phpspreadsheet
```

```php
<?php

// app/Exports/ProductsExport.php
namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with(['category', 'brand'])
            ->get()
            ->map(function ($product) {
                return [
                    'ID' => $product->id,
                    'SKU' => $product->sku,
                    '名称' => $product->name,
                    '分类' => $product->category?->name,
                    '品牌' => $product->brand?->name,
                    '价格' => $product->price,
                    '库存' => $product->stock,
                    '销量' => $product->sales_count,
                    '状态' => $product->is_active ? '启用' : '禁用',
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'SKU', '名称', '分类', '品牌', '价格', '库存', '销量', '状态'];
    }
}
```

```php
<?php

// app/Imports/ProductsImport.php
namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithValidation
{
    public function model(array $row)
    {
        return Product::create([
            'sku' => $row[0],
            'name' => $row[1],
            'price' => $row[2],
            'stock' => $row[3] ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|unique:products,sku',
            '1' => 'required',
            '2' => 'required|numeric|min:0',
        ];
    }
}
```

```php
<?php

// app/Http/Controllers/Admin/DataTransferController.php
class DataTransferController extends Controller
{
    public function exportProducts(Request $request)
    {
        return (new ProductsExport())->download('products-' . date('Ymd') . '.xlsx');
    }

    public function importProducts(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        Excel::import(new ProductsImport(), $request->file('file'));

        return back()->with('success', '导入成功');
    }
}
```

#### 11.3 订阅功能

参考Aimeos的Subscription：

```php
<?php

// database/migrations/xxxx_xx_xx_create_subscriptions_table.php
public function up(): void
{
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
        $table->foreignId('product_id')->constrained('products');
        $table->foreignId('sku_id')->constrained('product_skus');
        $table->string('status')->default('active'); // active, paused, cancelled, expired
        
        // 订阅周期
        $table->string('interval');  // day, week, month, year
        $table->integer('interval_count')->default(1);
        $table->decimal('price', 10, 2);
        
        // 时间
        $table->date('start_date');
        $table->date('end_date')->nullable();
        $table->date('next_payment_date');
        $table->date('last_payment_date')->nullable();
        
        $table->timestamps();

        $table->index('status');
        $table->index('next_payment_date');
    });
}
```

```php
<?php

// app/Console/Commands/ProcessSubscriptions.php
class ProcessSubscriptions extends Command
{
    protected $signature = 'subscriptions:process';

    public function handle(): int
    {
        $subscriptions = Subscription::where('status', 'active')
            ->where('next_payment_date', '<=', now())
            ->get();

        foreach ($subscriptions as $subscription) {
            try {
                DB::transaction(function () use ($subscription) {
                    // 创建订单
                    $order = app(OrderService::class)->createSubscriptionOrder($subscription);
                    
                    // 记录支付
                    $subscription->update([
                        'last_payment_date' => now(),
                        'next_payment_date' => $this->calculateNextPaymentDate($subscription),
                    ]);
                });
            } catch (\Exception $e) {
                \Log::error("Subscription payment failed: " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }

    protected function calculateNextPaymentDate(Subscription $subscription): \Carbon\Carbon
    {
        return match($subscription->interval) {
            'day' => now()->addDay($subscription->interval_count),
            'week' => now()->addWeek($subscription->interval_count),
            'month' => now()->addMonth($subscription->interval_count),
            'year' => now()->addYear($subscription->interval_count),
        };
    }
}
```

#### 11.4 库存管理（多仓库）

```php<?php

// database/migrations/xxxx_xx_xx_create_inventory_sources_table.php
public function up(): void
{
    Schema::create('inventory_sources', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->string('name');
        $table->text('address')->nullable();
        $table->string('contact')->nullable();
        $table->boolean('is_active')->default(true);
        $table->boolean('is_default')->default(false);
        $table->timestamps();
    });
}

Schema::create('inventory_stock', function (Blueprint $table) {
    $table->id();
    $table->foreignId('inventory_source_id')->constrained('inventory_sources')->cascadeOnDelete();
    $table->foreignId('sku_id')->constrained('product_skus')->cascadeOnDelete();
    $table->integer('quantity')->default(0);
    $table->integer('reserved_quantity')->default(0);  // 预留
    $table->timestamps();

    $table->unique(['inventory_source_id', 'sku_id']);
});
```

---

### 阶段八：前端页面开发

**预计时间：4天**

#### 8.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 8.1.1 | 安装配置Tailwind | 前端样式 |
| 8.1.2 | 首页开发 | 轮播、推荐产品 |
| 8.1.3 | 产品列表页 | 分类筛选、分页 |
| 8.1.4 | 产品详情页 | 图片、属性选择 |
| 8.1.5 | 购物车页面 | 数量修改 |
| 8.1.6 | 结账页面 | 地址、配送方式 |
| 8.1.7 | 用户中心 | 订单、地址 |

#### 8.2 安装Tailwind CSS

```bash
# 安装Tailwind
npm install -D tailwindcss postcss autoprefixer

# 初始化Tailwind
npx tailwindcss init -p
```

```javascript
// tailwind.config.js
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

```css
/* resources/css/app.css */
@tailwind base;
@tailwind components;
@tailwind utilities;
```

#### 8.3 前端目录结构

```
resources/views/
├── layouts/
│   ├── app.blade.php      # 主布局
│   ├── header.blade.php   # 头部导航
│   └── footer.blade.php   # 底部信息
├── home/
│   └── index.blade.php   # 首页
├── products/
│   ├── index.blade.php   # 产品列表
│   └── show.blade.php    # 产品详情
├── cart/
│   └── index.blade.php   # 购物车
├── checkout/
│   └── index.blade.php   # 结账
├── account/
│   ├── login.blade.php   # 登录
│   ├── register.blade.php# 注册
│   └── orders.blade.php # 订单列表
└── components/            # 可复用组件
    ├── product-card.blade.php
    ├── cart-item.blade.php
    └── footer.blade.php
```

#### 8.4 前端路由定义

```php
<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;

// 首页
Route::get('/', [HomeController::class, 'index'])->name('home');

// 产品
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// 购物车
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

// 结账
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// 用户账户（需要认证）
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/addresses', [AccountController::class, 'addresses'])->name('account.addresses');
    Route::get('/account/wishlist', [AccountController::class, 'wishlist'])->name('account.wishlist');
});
```

---

### 阶段九：插件系统开发

**预计时间：3天**

#### 9.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 9.1.1 | Hook系统 | 钩子机制 |
| 9.1.2 | 插件基础结构 | 插件框架 |
| 9.1.3 | 支付插件 | 示例支付插件 |
| 9.1.4 | 配送插件 | 示例配送插件 |

#### 9.2 Hook系统实现

参考BeikeShop的Hook设计：

```php
<?php

namespace App\Services;

class Hook
{
    protected static array $hooks = [];

    /**
     * 注册钩子
     */
    public static function listen(string $hook, callable $callback): void
    {
        self::$hooks[$hook][] = $callback;
    }

    /**
     * 触发钩子
     */
    public static function trigger(string $hook, array $params = []): array
    {
        $results = [];

        if (isset(self::$hooks[$hook])) {
            foreach (self::$hooks[$hook] as $callback) {
                $results[] = $callback($params);
            }
        }

        return $results;
    }

    /**
     * 检查钩子是否存在
     */
    public static function exists(string $hook): bool
    {
        return isset(self::$hooks[$hook]) && count(self::$hooks[$hook]) > 0;
    }
}
```

#### 9.3 在Blade中使用Hook

```blade.php
<!-- 产品详情页 - 购买按钮区域 -->
<div class="product-buy-section">
    <button class="btn-add-cart" data-id="{{ $product->id }}">
        加入购物车
    </button>
    
    {{-- 插件扩展点：购买按钮后 --}}
    @hook('product.detail.buy.after')
        <button class="btn-add-wishlist" data-id="{{ $product->id }}">
            <i class="icon-heart"></i> 添加收藏
        </button>
    @endhook
</div>

{{-- 插件扩展点：产品详情底部 --}}
@hook('product.detail.bottom')
    @include('partials.product-reviews')
@endhook
```

#### 9.4 插件基础结构

```
plugins/
├── PaymentExample/           # 支付插件示例
│   ├── config.json           # 插件配置
│   ├── Bootstrap.php          # 插件入口
│   ├── Controllers/
│   │   └── PaymentController.php
│   └── Resources/
│       └── views/
│           └── checkout.blade.php
│
└── ShippingFlat/             # 配送插件示例
    ├── config.json
    ├── Bootstrap.php
    └── Services/
        └── FlatShipping.php
```

**插件配置文件示例：**

```json
{
    "name": "PaymentExample",
    "display_name": "示例支付",
    "type": "payment",
    "description": "这是一个示例支付插件",
    "version": "1.0.0",
    "author": "Your Name",
    "settings": {
        "merchant_id": {
            "type": "text",
            "label": "商户号",
            "required": true
        },
        "api_key": {
            "type": "password",
            "label": "API密钥",
            "required": true
        },
        "sandbox": {
            "type": "boolean",
            "label": "沙箱模式",
            "default": true
        }
    }
}
```

**插件入口文件示例：**

```php
<?php

namespace Plugins\PaymentExample;

use System\Classes\BasePlugin;

class Plugin extends BasePlugin
{
    /**
     * 注册插件
     */
    public function register()
    {
        // 注册支付方式
        \Hook::listen('checkout.payment.methods', function (&$methods) {
            $methods['example_payment'] = [
                'name' => '示例支付',
                'code' => 'example_payment',
                'description' => '使用示例支付网关',
                'sort' => 100,
            ];
        });
    }

    /**
     * 启动插件
     */
    public function boot()
    {
        // 注册路由
        $this->loadRoutes();
        
        // 注册视图
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'payment-example');
    }

    /**
     * 插件设置
     */
    public function getSettings()
    {
        return config('plugins.payment_example.settings', []);
    }
}
```

---

### 阶段十：测试与部署

**预计时间：2天**

#### 10.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 10.1.1 | 单元测试 | 核心逻辑测试 |
| 10.1.2 | 功能测试 | 业务流程测试 |
| 10.1.3 | 生产环境配置 | 优化配置 |
| 10.1.4 | 部署上线 | 项目上线 |

#### 10.2 创建测试

```bash
# 创建购物车测试
php artisan make:test CartTest

# 创建订单测试
php artisan make:test OrderTest

# 创建产品测试
php artisan make:test ProductTest
```

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\ProductSku;
use App\Models\Cart;

class CartTest extends TestCase
{
    /**
     * 测试客户可以添加产品到购物车
     */
    public function test_customer_can_add_product_to_cart(): void
    {
        // 创建测试用户和产品
        $customer = Customer::factory()->create();
        $sku = ProductSku::factory()->create();

        // 发送添加购物车请求
        $response = $this->actingAs($customer, 'customer')
            ->postJson('/api/cart', [
                'sku_id' => $sku->id,
                'quantity' => 1
            ]);

        // 断言响应成功
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => '已添加到购物车'
            ]);

        // 断言购物车记录已创建
        $this->assertDatabaseHas('carts', [
            'customer_id' => $customer->id,
            'sku_id' => $sku->id,
            'quantity' => 1
        ]);
    }

    /**
     * 测试客户可以查看购物车
     */
    public function test_customer_can_view_cart(): void
    {
        $customer = Customer::factory()->create();
        
        // 创建购物车记录
        Cart::factory()->count(3)->create([
            'customer_id' => $customer->id
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data.items');
    }
}
```

#### 10.3 运行测试

```bash
# 运行所有测试
php artisan test

# 运行指定测试
php artisan test --filter=CartTest

# 运行单元测试
php artisan test --unit

# 运行功能测试
php artisan test --feature
```

#### 10.4 生产环境配置

```env
# .env.production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# 数据库
DB_CONNECTION=mysql
DB_HOST=your_mysql_host
DB_PORT=3306
DB_DATABASE=production_db
DB_USERNAME=prod_user
DB_PASSWORD=prod_password

# 队列
QUEUE_CONNECTION=redis

# 缓存
CACHE_DRIVER=redis
SESSION_DRIVER=redis

# 邮件
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null

# 文件存储
FILESYSTEM_DISK=s3
```

#### 10.5 性能优化

```php
// routes/api.php - 添加路由缓存
Route::group(['middleware' => 'cache.headers:public;max_age=3600'], function () {
    // 产品列表等不需要实时更新的接口
});
```

---

## 四、开发检查清单

### 4.1 基础检查

- [ ] PHP 8.2+ 环境安装完成
- [ ] Composer 安装完成
- [ ] MySQL 8.0 安装完成
- [ ] Node.js 20+ 安装完成
- [ ] Laravel 12 项目创建完成
- [ ] .env 配置完成
- [ ] 密钥生成完成

### 4.2 数据库检查

- [ ] 产品表创建完成
- [ ] SKU表创建完成
- [ ] 分类表创建完成
- [ ] 品牌表创建完成
- [ ] 客户表创建完成
- [ ] 订单表创建完成
- [ ] 购物车表创建完成
- [ ] 迁移执行成功

### 4.3 API检查

- [ ] 产品列表API
- [ ] 产品详情API
- [ ] 分类列表API
- [ ] 品牌列表API
- [ ] 购物车API（添加/删除/更新）
- [ ] 订单创建API
- [ ] 用户注册API
- [ ] 用户登录API

### 4.4 前端检查

- [ ] 首页正常显示
- [ ] 产品列表正常显示（带分页）
- [ ] 产品详情正常显示
- [ ] 购物车功能正常
- [ ] 结账流程正常
- [ ] 用户登录注册正常
- [ ] 用户中心正常

### 4.5 后台检查

- [ ] 管理员登录正常
- [ ] 商品管理CRUD正常
- [ ] 订单管理正常
- [ ] 客户管理正常
- [ ] 权限配置正常

---

## 五、常用命令速查

### 5.1 Artisan命令

```bash
# 创建控制器
php artisan make:controller ControllerName
php artisan make:controller ControllerName --resource

# 创建模型
php artisan make:model ModelName
php artisan make:model ModelName -mcr  # 同时创建迁移和控制器

# 创建迁移
php artisan make:migration create_table_name_table

# 创建中间件
php artisan make:middleware MiddlewareName

# 创建请求验证
php artisan make:request StoreProductRequest

# 创建资源
php artisan make:resource ProductResource
```

### 5.2 数据库命令

```bash
# 运行迁移
php artisan migrate

# 回滚上一次迁移
php artisan migrate:rollback

# 回滚所有迁移
php artisan migrate:fresh

# 填充数据
php artisan db:seed
php artisan db:seed --class=ProductSeeder

# 查看SQL日志
php artisan tinker
```

### 5.3 缓存命令

```bash
# 清除所有缓存
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 缓存配置
php artisan config:cache
php artisan route:cache
```

### 5.4 开发命令

```bash
# 启动开发服务器
php artisan serve

# 监听文件变化自动重新编译
npm run dev

# 生产环境构建
npm run build

# 运行测试
php artisan test
```

---

## 六、推荐学习资源

1. **Laravel官方文档**: https://laravel.com/docs/12.x
2. **Laravel China**: https://learn.laravel.cn
3. **Spatie Permission文档**: https://spatie.be/docs/laravel-permission
4. **Tailwind CSS文档**: https://tailwindcss.com/docs
5. **Laravel Breeze**: https://laravel.com/docs/12.x/starter-kits

---

## 阶段十五：小程序/App API对接

**预计时间：3天**

#### 15.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 15.1.1 | 微信小程序API | 登录/支付/分享 |
| 15.1.2 | App API | RN/Flutter接口 |
| 15.1.3 | 第三方API | 开放平台接口 |

#### 15.2 微信小程序支持

```bash
# 安装EasyWeChat
composer require w7corp/easywechat
```

```php
<?php

// config/wechat.php
return [
    'mini_program' => [
        'app_id' => env('WECHAT_MINI_APP_ID'),
        'secret' => env('WECHAT_MINI_SECRET'),
        'token' => env('WECHAT_TOKEN'),
        'aes_key' => env('WECHAT_AES_KEY'),
    ],
];
```

```php
<?php

// app/Http/Controllers/Api/WechatController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\WechatService;
use Illuminate\Http\Request;

class WechatController extends Controller
{
    protected WechatService $wechatService;

    public function __construct(WechatService $wechatService)
    {
        $this->wechatService = $wechatService;
    }

    /**
     * 微信登录
     */
    public function login(Request $request)
    {
        $code = $request->input('code');
        
        $result = $this->wechatService->miniProgramLogin($code);
        
        // 创建或更新用户
        $customer = Customer::updateOrCreate(
            ['wechat_openid' => $result['openid']],
            [
                'wechat_unionid' => $result['unionid'] ?? null,
                'wechat_session_key' => $result['session_key'],
            ]
        );

        $token = $customer->createToken('wechat-mini')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'customer' => $customer,
            ]
        ]);
    }

    /**
     * 获取手机号
     */
    public function getPhoneNumber(Request $request)
    {
        $encryptedData = $request->input('encryptedData');
        $iv = $request->input('iv');

        $phone = $this->wechatService->decryptPhoneNumber($encryptedData, $iv);

        $customer = auth('sanctum')->user();
        $customer->update(['phone' => $phone]);

        return response()->json(['success' => true, 'phone' => $phone]);
    }
}
```

#### 15.3 App API 设计

```php
<?php

// routes/api/app.php
Route::prefix('app')->group(function () {
    // 公开接口
    Route::get('/home', [AppHomeController::class, 'index']);
    Route::get('/products', [AppProductController::class, 'index']);
    Route::get('/categories', [AppCategoryController::class, 'index']);
    
    // 需要认证
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AppUserController::class, 'profile']);
        Route::get('/cart', [AppCartController::class, 'index']);
        Route::post('/cart/add', [AppCartController::class, 'add']);
        Route::get('/orders', [AppOrderController::class, 'index']);
    });
});
```

---

## 阶段十六：Next.js多端前端

**预计时间：5天**

#### 16.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 16.1.1 | Next.js项目搭建 | 项目骨架 |
| 16.1.2 | 微信小程序 | 商城小程序 |
| 16.1.3 | H5响应式 | 移动端H5 |
| 16.1.4 | App集成 | React Native |

#### 16.2 技术栈

| 技术 | 用途 |
|------|------|
| Next.js 14 | React框架 |
| Tailwind CSS | 样式 |
| Zustand | 状态管理 |
| React Query | 数据请求 |
| tRPC/API | 接口调用 |
| PWA | 离线支持 |

#### 16.3 目录结构

```
newshop-client/
├── app/                    # Next.js 14 App Router
│   ├── (shop)/            # 商城模块
│   │   ├── page.tsx      # 首页
│   │   ├── products/      # 产品
│   │   └── cart/         # 购物车
│   ├── (account)/        # 用户模块
│   └── api/              # API代理
├── components/            # 组件库
│   ├── ui/               # 基础组件
│   └── shop/             # 商城组件
├── lib/                   # 工具
├── hooks/                 # 自定义Hook
├── store/                 # 状态管理
└── public/                # 静态资源
```

#### 16.4 微信小程序适配

```tsx
// app.json - 小程序配置
{
  "pages": [
    "pages/index/index",
    "pages/products/index",
    "pages/products/show",
    "pages/cart/index",
    "pages/user/index"
  ],
  "window": {
    "navigationBarTitleText": "商城"
  },
  "tabBar": {
    "list": [
      { "pagePath": "pages/index/index", "text": "首页" },
      { "pagePath": "pages/cart/index", "text": "购物车" },
      { "pagePath": "pages/user/index", "text": "我的" }
    ]
  }
}
```

---

## 阶段十七：第三方平台对接

**预计时间：2天**

#### 17.1 关键节点

| 节点 | 任务 | 交付物 |
|------|------|--------|
| 17.1.1 | 微信支付 | 公众号/小程序支付 |
| 17.1.2 | 支付宝 | 即时到账/手机网站 |
| 17.1.3 | 物流API | 快递鸟/菜鸟 |
| 17.1.4 | 短信/邮件 | 通知服务 |

#### 17.2 支付集成

```php
<?php

// app/Services/PaymentService.php
namespace App\Services;

class PaymentService
{
    /**
     * 微信支付
     */
    public function wechatPay(Order $order, string $tradeType = 'JSAPI')
    {
        $params = [
            'appid' => config('wechat.app_id'),
            'mch_id' => config('wechat.mch_id'),
            'nonce_str' => Str::random(32),
            'body' => '订单支付',
            'out_trade_no' => $order->order_number,
            'total_fee' => $order->total * 100,
            'spbill_create_ip' => request()->ip(),
            'notify_url' => route('api.payment.wechat.notify'),
            'trade_type' => $tradeType,
        ];

        $params['sign'] = $this->wechatSign($params);

        $response = Http::post('https://api.mch.weixin.qq.com/pay/unifiedorder', $params);

        return $this->parseWechatResponse($response);
    }

    /**
     * 支付宝支付
     */
    public function alipay(Order $order)
    {
        $alipay = new \AlipayTradePayContentBuilder();
        $alipay->setOutTradeNo($order->order_number);
        $alipay->setTotalAmount($order->total);
        $alipay->setSubject('订单支付');

        return $alipay->getSdkDirectPayContent();
    }
}
```

#### 17.3 物流查询

```php
<?php

// app/Services/LogisticsService.php
namespace App\Services;

class LogisticsService
{
    /**
     * 查询物流
     */
    public function query(string $company, string $number): array
    {
        return match($company) {
            'shunfeng' => $this->shunfengQuery($number),
            'yunda' => $this->yundaQuery($number),
            'zhongtong' => $this->zhongtongQuery($number),
            default => $this->kuaidi100Query($company, $number),
        };
    }

    /**
     * 快递100查询
     */
    protected function kuaidi100Query(string $company, string $number): array
    {
        $response = Http::post('https://api.kuaidi100.com/api', [
            'com' => $company,
            'nu' => $number,
            'phone' => '',
            'show' => 0,
            'muti' => 1,
        ]);

        return json_decode($response, true);
    }
}
```

---

## 八、技术架构总结

本项目综合了三个电商系统的最佳实践：

| 特性 | 参考来源 | 实现方式 |
|------|----------|----------|
| 模块化架构 | Bagisto (35+模块) | 按功能模块分离 |
| 数据层设计 | Aimeos (MShop) | Repository模式 |
| 后台管理 | BeikeShop (50+控制器) | 完整CRUD |
| 插件系统 | BeikeShop (Hook) | 钩子机制 |
| 前端样式 | Aimeos (Tailwind) | CSS框架 |

---

*文档版本: 1.0*
*创建日期: 2026-02-17*
*目标读者: 3年PHP经验，无Laravel经验*

### 路由治理与端点划分 (追加)
- 端点治理草案已就绪，你可以在 PRD 中正式落地。

---

## 阶段三完成状态（2026-03-03）

### 已完成功能

| 功能 | 状态 | 说明 |
|------|------|------|
| 前后端用户分离 | ✅ | customers表 + customer guard |
| 客户登录/注册 | ✅ | /customer/login, /customer/register |
| 商品浏览 | ✅ | /products, /products/{id} |
| 购物车 | ✅ | 数据库存储、数量修改、删除 |
| 收货地址 | ✅ | CRUD + 默认地址 |
| 订单管理 | ✅ | 下单、列表、详情、取消 |

### 访问地址（阶段三已完成）

| 页面 | 地址 |
|------|------|
| 首页 | http://test.lara2602.local/ |
| 商品列表 | http://test.lara2602.local/products |
| 客户登录 | http://test.lara2602.local/customer/login |
| 客户注册 | http://test.lara2602.local/customer/register |
| 后台登录 | http://test.lara2602.local/admin/login |
| 购物车 | http://test.lara2602.local/cart |
| 收货地址 | http://test.lara2602.local/addresses |
| 我的订单 | http://test.lara2602.local/orders |

### 阶段三交付物清单

#### 数据库迁移
- 
- 
- 
- 
- 
- 
- 

#### 模型
- 
- 
- 
- 
- 

#### 控制器
- 
- 
- 
- 
- 

#### 视图
- 
- 

---

## 阶段三完成状态（2026-03-03）

### 已完成功能

| 功能 | 状态 | 说明 |
|------|------|------|
| 前后端用户分离 | ✅ | customers表 + customer guard |
| 客户登录/注册 | ✅ | /customer/login, /customer/register |
| 商品浏览 | ✅ | /products, /products/{id} |
| 购物车 | ✅ | 数据库存储、数量修改、删除 |
| 收货地址 | ✅ | CRUD + 默认地址 |
| 订单管理 | ✅ | 下单、列表、详情、取消 |

### 访问地址（阶段三已完成）

| 页面 | 地址 |
|------|------|
| 首页 | http://test.lara2602.local/ |
| 商品列表 | http://test.lara2602.local/products |
| 客户登录 | http://test.lara2602.local/customer/login |
| 客户注册 | http://test.lara2602.local/customer/register |
| 后台登录 | http://test.lara2602.local/admin/login |
| 购物车 | http://test.lara2602.local/cart |
| 收货地址 | http://test.lara2602.local/addresses |
| 我的订单 | http://test.lara2602.local/orders |
