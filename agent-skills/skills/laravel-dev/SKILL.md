---
name: laravel-dev
description: Laravel 12 开发环境配置 - 本项目特定环境配置、依赖版本和开发命令。使用场景：创建 Laravel 项目、安装依赖、配置数据库、运行迁移等。
---

# Laravel 12 开发环境

本 Skill 记录本项目的开发环境配置信息。

## 项目信息

| 项目 | 值 |
|------|-----|
| 项目名称 | newshop |
| 项目位置 | `D:\phpstudy_pro\WWW\lara2602\newshop\` |
| Laravel 版本 | 12.51.0 |
| PHP 版本 | 8.2.9 |

## 环境要求

| 工具 | 最低版本 | 本项目版本 |
|------|----------|------------|
| PHP | 8.2+ | 8.2.9 |
| Composer | 2.0+ | 2.5.8 |
| Node.js | 20+ | 24.11.1 |
| MySQL | 8.0+ | - |

## 已安装依赖

```bash
# 认证脚手架
composer require laravel/breeze --dev

# API 认证
composer require laravel/sanctum

# 权限管理
composer require spatie/laravel-permission
```

**版本信息：**
- laravel/breeze: v2.3.8
- laravel/sanctum: v4.3.1
- spatie/laravel-permission: v6.24.1 (注：PHP 8.2 不满足 v7.x 要求，降级安装)

## 数据库配置

**.env 配置示例：**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_2602ai
DB_USERNAME=root
DB_PASSWORD=<密码>
```

## 常用命令

```bash
# 进入项目目录
cd newshop

# 安装依赖
composer install
npm install

# 生成应用密钥
php artisan key:generate

# 运行数据库迁移
php artisan migrate

# 强制运行迁移
php artisan migrate --force

# 创建迁移
php artisan make:migration create_<table>_table

# 创建模型
php artisan make:model <ModelName>

# 创建控制器
php artisan make:controller <ControllerName>

# 启动开发服务器
php artisan serve
```

## phpstudy PRO 配置

1. 添加站点，域名：`test.lara2602.local`
2. 网站目录指向：`D:\phpstudy_pro\WWW\lara2602\newshop\public`
3. PHP 版本选择：php8.2.9nts
4. 访问地址：http://test.lara2602.local/

## 开发流程

1. 创建 Laravel 项目：`composer create-project laravel/laravel newshop "12.*"`
2. 安装依赖：进入项目目录执行 `composer install`
3. 配置 .env 文件
4. 生成密钥：`php artisan key:generate`
5. 创建数据库并配置 .env
6. 运行迁移：`php artisan migrate`
7. 安装前端依赖：`npm install`
8. 配置站点并启动

## 注意事项

- Windows 环境下使用 phpstudy PRO 简化环境配置
- spatie/laravel-permission v7.x 需要 PHP 8.3+，当前环境使用 v6.24.1
- 数据库名需要在 MySQL 中提前创建，或让 Laravel 自动创建

## 常见问题

### 1. Eloquent 关联指定外键

当模型关联的外键不是默认命名时，需要手动指定：

```php
// 默认：外键为 category_id
public function category(): BelongsTo
{
    return $this->belongsTo(ProductCategory::class);
}

// 指定外键
public function category(): BelongsTo
{
    return $this->belongsTo(ProductCategory::class, 'category_id');
}

// 指定外键和关联键
public function products(): HasMany
{
    return $this->hasMany(Product::class, 'category_id', 'id');
}
```

### 2. 菜单权限动态显示

根据用户权限动态显示/隐藏侧边栏菜单：

```php
$user = Auth::user();
$canViewUsers = $user && ($user->can('user list') || $user->hasRole('admin'));
$canViewProducts = $user && ($user->can('product list') || $user->hasRole('admin'));

@if($canViewUsers)
<a href="{{ route('users.index') }}">用户管理</a>
@endif
```

### 3. 数据库唯一约束修改

修改已有唯一约束时，需要先删除外键约束：

```php
Schema::table('product_attribute_values', function (Blueprint $table) {
    $table->dropForeign(['product_id']);
    $table->dropForeign(['attribute_id']);
    $table->dropForeign(['attribute_value_id']);
    $table->dropUnique(['product_id', 'attribute_id']);
    $table->index(['product_id', 'attribute_id']);
    // 重新添加外键
    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
});
```

### 4. API 路由名称冲突

Web 和 API 路由使用相同名称时，需要给 API 路由添加前缀：

```php
// api.php
Route::apiResource('users', UserApiController::class)->names('api.users');
// 路由名称变为：api.users.index, api.users.store 等
```

### 5. Spatie Permission 权限检查

```php
// 检查单个权限
$user->can('post list');

// 检查角色
$user->hasRole('admin');

// 检查多个权限（满足其一）
$user->can(['post create', 'post edit']);

// 检查多个权限（全部满足）
$user->can(['post create', 'post edit'], true);
```
