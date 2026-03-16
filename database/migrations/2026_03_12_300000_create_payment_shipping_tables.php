<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 支付方式表
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // 支付方式名称
            $table->string('code')->unique();         // 支付代码
            $table->text('description')->nullable();   // 描述
            $table->string('icon')->nullable();        // 图标
            $table->integer('order')->default(0);     // 排序
            $table->boolean('is_enabled')->default(true);  // 是否启用
            $table->json('config')->nullable();       // 配置信息
            $table->timestamps();
        });

        // 配送方式表
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // 配送方式名称
            $table->string('code')->unique();         // 配送代码
            $table->text('description')->nullable();   // 描述
            $table->decimal('first_weight', 10, 2)->default(1);  // 首重
            $table->decimal('first_price', 10, 2)->default(0);   // 首重价格
            $table->decimal('continue_weight', 10, 2)->default(1); // 续重
            $table->decimal('continue_price', 10, 2)->default(0);  // 续重价格
            $table->decimal('free_shipping_amount', 10, 2)->nullable();  // 满多少免运费
            $table->integer('estimated_days')->nullable();  // 预计天数
            $table->integer('order')->default(0);     // 排序
            $table->boolean('is_enabled')->default(true);  // 是否启用
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('payment_methods');
    }
};
