<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['attribute_id']);
            $table->dropForeign(['attribute_value_id']);
            $table->dropUnique(['product_id', 'attribute_id']);
            $table->index(['product_id', 'attribute_id']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['attribute_id']);
            $table->dropForeign(['attribute_value_id']);
            $table->dropIndex(['product_id', 'attribute_id']);
            $table->unique(['product_id', 'attribute_id']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->nullOnDelete();
        });
    }
};
