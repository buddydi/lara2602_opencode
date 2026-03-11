<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('phone');
            $table->string('member_level')->default('bronze')->after('points');
        });

        Schema::create('points_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('points')->comment('积分数量（正数增加，负数扣减）');
            $table->string('type')->comment('类型：order_complete-订单完成、order_use-订单使用、admin_add-管理员添加、admin_deduct-管理员扣减');
            $table->string('description')->nullable()->comment('描述');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points_records');
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['points', 'member_level']);
        });
    }
};
