<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 商品表
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->string('cover_image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
        });

        // SKU 表
        Schema::create('product_skus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('sku')->unique();
            $table->string('name')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('sales')->default(0);
            $table->json('attributes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        // 商品属性表
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['select', 'text', 'number'])->default('select');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 属性值表
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->string('value');
            $table->string('code')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->foreign('attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->unique(['attribute_id', 'value']);
        });

        // 商品属性关联表
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('attribute_value_id')->nullable();
            $table->string('text_value')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->nullOnDelete();
            $table->index(['product_id', 'attribute_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_skus');
        Schema::dropIfExists('products');
    }
};
