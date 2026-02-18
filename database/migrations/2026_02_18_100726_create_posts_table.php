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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');           // 标题
            $table->text('content');           // 内容
            $table->string('slug')->unique();  // slug 友好 URL
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // 作者
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');  // 状态
            $table->timestamp('published_at')->nullable();  // 发布时间
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
