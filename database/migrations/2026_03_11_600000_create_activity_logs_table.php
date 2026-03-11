<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('module');           // 模块：order, product, user 等
            $table->string('action');           // 操作：create, update, delete, login 等
            $table->string('description');     // 描述
            $table->string('target_type')->nullable();   // 关联模型
            $table->unsignedBigInteger('target_id')->nullable();  // 关联ID
            $table->json('old_values')->nullable();    // 修改前的数据
            $table->json('new_values')->nullable();    // 修改后的数据
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['module', 'action']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
