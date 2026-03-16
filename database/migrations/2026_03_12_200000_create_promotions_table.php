<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 促销活动表
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // 活动名称
            $table->string('type');                   // 活动类型：flash_sale(秒杀), discount(折扣), full_reduce(满减)
            $table->text('description')->nullable();  // 活动描述
            $table->decimal('discount_rate', 5, 2)->nullable();  // 折扣率（如 80 表示 8 折）
            $table->decimal('discount_amount', 10, 2)->nullable(); // 固定折扣金额
            $table->decimal('min_amount', 10, 2)->nullable();      // 最低消费金额（满减用）
            $table->decimal('reduce_amount', 10, 2)->nullable();   // 减掉金额（满减用）
            $table->timestamp('start_time');         // 开始时间
            $table->timestamp('end_time');           // 结束时间
            $table->integer('max_quantity')->nullable(); // 最大限购数量（秒杀用）
            $table->integer('sold_quantity')->default(0); // 已售数量
            $table->integer('max_per_user')->nullable(); // 每人限购
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_active');
        });

        // 活动商品关联表
        Schema::create('promotion_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('special_price', 10, 2)->nullable();  // 活动价格
            $table->integer('stock_limit')->nullable();          // 活动库存限制
            $table->integer('sold_count')->default(0);           // 已售数量
            $table->timestamps();
            
            $table->unique(['promotion_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('promotions');
    }
};
