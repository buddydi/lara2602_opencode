<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('优惠码');
            $table->string('name')->comment('优惠券名称');
            $table->string('type')->comment('类型：fixed-固定金额, percentage-百分比折扣');
            $table->decimal('value', 10, 2)->comment('优惠值');
            $table->decimal('min_amount', 10, 2)->default(0)->comment('最低消费金额');
            $table->decimal('max_discount', 10, 2)->nullable()->comment('最高优惠金额（百分比类型用）');
            $table->dateTime('start_date')->comment('开始时间');
            $table->dateTime('end_date')->comment('结束时间');
            $table->integer('usage_limit')->default(0)->comment('使用次数限制（0=不限）');
            $table->integer('usage_count')->default(0)->comment('已使用次数');
            $table->integer('per_user_limit')->default(1)->comment('每人限领次数');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->text('description')->nullable()->comment('描述');
            $table->timestamps();
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('used_count')->default(1)->comment('使用次数');
            $table->dateTime('used_at')->comment('使用时间');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
    }
};
