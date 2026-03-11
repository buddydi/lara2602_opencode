<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('after_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type')->comment('类型：return-退货, exchange-换货');
            $table->string('status')->default('pending')->comment('状态：pending-待处理, processing-处理中, completed-已完成, rejected-已拒绝');
            $table->string('reason')->comment('原因');
            $table->text('description')->nullable()->comment('详细说明');
            $table->text('admin_remark')->nullable()->comment('管理员备注');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('after_sales');
    }
};
