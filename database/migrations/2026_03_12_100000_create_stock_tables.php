<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 库存预警设置表
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_sku_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('low_stock_threshold')->default(10);    // 低库存预警阈值
            $table->integer('critical_stock_threshold')->default(5); // 紧急库存阈值
            $table->boolean('is_enabled')->default(true);          // 是否启用预警
            $table->boolean('notify_admin')->default(true);         // 是否通知管理员
            $table->boolean('notify_customer')->default(false);    // 是否通知客户
            $table->timestamps();
            
            $table->unique(['product_id', 'product_sku_id']);
        });

        // 出入库记录表
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_sku_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['in', 'out', 'adjust']);  // 入库、出库、调整
            $table->integer('quantity');                    // 数量（正数入库，负数出库）
            $table->integer('balance');                     // 变动后库存
            $table->string('order_no')->nullable();         // 相关订单号
            $table->string('reason');                       // 原因
            $table->text('remark')->nullable();              // 备注
            $table->timestamps();
            
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
        Schema::dropIfExists('stock_alerts');
    }
};
