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
        Schema::create('api_endpoints', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('接口名称');
            $table->string('method', 10)->comment('请求方法');
            $table->string('path')->comment('接口路径');
            $table->string('group')->default('default')->comment('分组');
            $table->text('description')->nullable()->comment('描述');
            $table->text('parameters')->nullable()->comment('参数说明JSON');
            $table->text('response')->nullable()->comment('响应示例');
            $table->boolean('auth_required')->default(false)->comment('是否需要认证');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
            
            $table->unique(['method', 'path']);
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_endpoints');
    }
};
