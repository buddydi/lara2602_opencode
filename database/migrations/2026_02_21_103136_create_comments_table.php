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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');  // 文章ID
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // 评论用户
            $table->text('content');                                          // 评论内容
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');  // 父评论（回复）
            $table->boolean('is_approved')->default(true);                    // 是否审核通过
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
