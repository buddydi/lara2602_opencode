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
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Token名称');
            $table->string('token', 64)->unique()->comment('Token值');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->comment('关联用户');
            $table->string('guard')->default('sanctum')->comment('认证guard');
            $table->text('abilities')->nullable()->comment('权限JSON');
            $table->timestamp('expires_at')->nullable()->comment('过期时间');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->text('description')->nullable()->comment('描述');
            $table->timestamp('last_used_at')->nullable()->comment('最后使用时间');
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
    }
};
