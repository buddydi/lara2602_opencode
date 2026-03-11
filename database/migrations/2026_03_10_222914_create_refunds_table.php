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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2)->comment('退款金额');
            $table->string('reason')->comment('退款原因');
            $table->text('description')->nullable()->comment('详细说明');
            $table->string('status')->default('pending')->comment('状态:pending待审核,approved已退款,rejected已拒绝');
            $table->text('reject_reason')->nullable()->comment('拒绝原因');
            $table->timestamp('processed_at')->nullable()->comment('处理时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
