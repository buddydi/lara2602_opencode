<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('type')->comment('类型：order-订单, refund-退款, system-系统');
            $table->string('title')->comment('标题');
            $table->text('content')->comment('内容');
            $table->string('status')->default('unread')->comment('状态：unread-未读, read-已读');
            $table->json('data')->nullable()->comment('扩展数据');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
