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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // 收货人姓名
            $table->string('phone', 20); // 联系电话
            $table->string('province'); // 省份
            $table->string('city'); // 城市
            $table->string('district'); // 区/县
            $table->string('detail_address'); // 详细地址
            $table->string('postal_code', 10)->nullable(); // 邮政编码
            $table->boolean('is_default')->default(false); // 是否默认
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
