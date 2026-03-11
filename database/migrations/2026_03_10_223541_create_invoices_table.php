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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('type')->comment('发票类型:personal个人,company企业');
            $table->string('title')->nullable()->comment('发票抬头');
            $table->string('tax_no')->nullable()->comment('税号');
            $table->string('email')->nullable()->comment('邮箱');
            $table->string('phone')->nullable()->comment('电话');
            $table->string('address')->nullable()->comment('地址');
            $table->decimal('amount', 10, 2)->comment('开票金额');
            $table->string('status')->default('pending')->comment('状态:pending待开,issued已开');
            $table->string('invoice_no')->nullable()->comment('发票号');
            $table->timestamp('issued_at')->nullable()->comment('开票时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
