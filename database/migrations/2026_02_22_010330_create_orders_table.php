<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('order_no')->unique(); // 订单编号
            $table->decimal('total_amount', 10, 2); // 订单总金额
            $table->decimal('pay_amount', 10, 2)->default(0); // 实付金额
            $table->decimal('freight', 10, 2)->default(0); // 运费
            $table->integer('product_count')->default(0); // 商品数量
            $table->enum('status', ['pending', 'paid', 'shipping', 'shipped', 'completed', 'cancelled', 'refunded'])->default('pending'); // 订单状态
            $table->string('pay_method')->nullable(); // 支付方式
            $table->datetime('paid_at')->nullable(); // 支付时间
            $table->string('shipping_no')->nullable(); // 快递单号
            $table->datetime('shipped_at')->nullable(); // 发货时间
            $table->text('remark')->nullable(); // 订单备注
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_sku_id')->nullable()->constrained('product_skus')->nullOnDelete();
            $table->string('product_name'); // 商品名称
            $table->string('product_image')->nullable(); // 商品图片
            $table->string('sku_name')->nullable(); // SKU规格
            $table->decimal('price', 10, 2); // 单价
            $table->integer('quantity'); // 数量
            $table->decimal('total', 10, 2); // 小计
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
