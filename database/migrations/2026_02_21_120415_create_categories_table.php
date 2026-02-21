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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // 分类名称
            $table->string('slug')->unique();           // 友好 URL
            $table->text('description')->nullable();    // 描述
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');  // 父分类
            $table->integer('order')->default(0);       // 排序
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
