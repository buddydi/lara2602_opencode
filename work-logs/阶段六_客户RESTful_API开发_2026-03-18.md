# 阶段六：客户 RESTful API 开发

**日期**：2026-03-18

---

## 阶段目标

为商城开发完整的客户 RESTful API，使用 Laravel Sanctum 进行认证。

---

## 完成功能

### 1. Laravel Sanctum 认证配置

| 配置项 | 文件 | 说明 |
|--------|------|------|
| Sanctum 安装 | composer.json | 已安装 sanctum |
| 模型配置 | config/sanctum.php | 指定 Customer 模型 |
| Guard 配置 | config/auth.php | 添加 sanctum guard |
| HasApiTokens | app/Models/Customer.php | 已添加 |

### 2. API 控制器

| 文件 | 功能 |
|------|------|
| CustomerAuthController | 登录/注册/登出/个人信息 |
| CartController | 购物车 CRUD |
| AddressController | 收货地址 CRUD |
| OrderController | 订单管理 |

### 3. 数据库修复

| 迁移文件 | 修复内容 |
|----------|----------|
| add_status_to_customers_table | customers 表添加 status 字段 |
| add_sku_id_to_cart_items_table | cart_items 表添加 sku_id 字段 |
| add_price_to_cart_items_table | cart_items 表添加 price 字段 |
| fix_cart_items_foreign_key | 外键从 users 改为 customers |
| fix_addresses_foreign_key | 外键从 users 改为 customers |
| fix_orders_foreign_key | 外键从 users 改为 customers |

### 4. 模型修复

| 文件 | 修复内容 |
|------|----------|
| CartItem.php | 添加 price 字段支持 |
| OrderController.php | subtotal → total |

---

## API 端点

### 客户认证

| 端点 | 方法 | 说明 | 认证 |
|------|------|------|------|
| /api/customer/register | POST | 客户注册 | 否 |
| /api/customer/login | POST | 客户登录 | 否 |
| /api/customer/me | GET | 获取当前用户 | Bearer Token |
| /api/customer/profile | PUT | 更新个人信息 | Bearer Token |
| /api/customer/logout | POST | 退出登录 | Bearer Token |

### 收货地址

| 端点 | 方法 | 说明 | 认证 |
|------|------|------|------|
| /api/customer/addresses | GET | 地址列表 | Bearer Token |
| /api/customer/addresses | POST | 创建地址 | Bearer Token |
| /api/customer/addresses/{id} | GET | 地址详情 | Bearer Token |
| /api/customer/addresses/{id} | PUT | 更新地址 | Bearer Token |
| /api/customer/addresses/{id} | DELETE | 删除地址 | Bearer Token |

### 购物车

| 端点 | 方法 | 说明 | 认证 |
|------|------|------|------|
| /api/customer/cart | GET | 购物车列表 | Bearer Token |
| /api/customer/cart | POST | 添加商品 | Bearer Token |
| /api/customer/cart/{id} | PUT | 更新数量 | Bearer Token |
| /api/customer/cart/{id} | DELETE | 删除商品 | Bearer Token |
| /api/customer/cart | DELETE | 清空购物车 | Bearer Token |

### 订单

| 端点 | 方法 | 说明 | 认证 |
|------|------|------|------|
| /api/customer/orders | GET | 订单列表 | Bearer Token |
| /api/customer/orders | POST | 创建订单 | Bearer Token |
| /api/customer/orders/{id} | GET | 订单详情 | Bearer Token |
| /api/customer/orders/{id}/cancel | POST | 取消订单 | Bearer Token |

---

## 测试结果

| API | 方法 | 状态 |
|-----|------|------|
| 客户登录 | POST /api/customer/login | ✅ |
| 获取当前用户 | GET /api/customer/me | ✅ |
| 创建收货地址 | POST /api/customer/addresses | ✅ |
| 获取收货地址 | GET /api/customer/addresses | ✅ |
| 添加购物车 | POST /api/customer/cart | ✅ |
| 获取购物车 | GET /api/customer/cart | ✅ |
| 清空购物车 | DELETE /api/customer/cart | ✅ |
| 创建订单 | POST /api/customer/orders | ✅ |
| 获取订单列表 | GET /api/customer/orders | ✅ |
| 取消订单 | POST /api/customer/orders/{id}/cancel | ✅ |

---

## Git 提交

| 提交 | 内容 |
|------|------|
| 9561074 | feat: 添加客户RESTful API（Laravel Sanctum认证） |

---

## 核心技能

| 技能 | 说明 |
|------|------|
| Laravel Sanctum | API 令牌认证 |
| RESTful API | 标准接口设计 |
| 外键修复 | 多表关联调整 |

---

## 待优化项

- [ ] 中文数据编码问题（Form Data 传输中文时有问题，建议前端使用 JSON）
- [ ] 订单项的 product_sku_id 映射（数据库用 sku_id，代码用 product_sku_id）
