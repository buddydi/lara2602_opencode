<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points_rules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('规则键名');
            $table->string('value')->comment('规则值');
            $table->string('description')->nullable()->comment('说明');
            $table->timestamps();
        });

        $rules = [
            ['key' => 'points_rate', 'value' => '1', 'description' => '消费获得积分比例（1元=N积分）'],
            ['key' => 'deduction_rate', 'value' => '100', 'description' => '积分抵扣比例（N积分=1元）'],
            ['key' => 'max_deduction', 'value' => '10000', 'description' => '单次订单最大使用积分'],
            ['key' => 'bronze_min', 'value' => '0', 'description' => '青铜会员最低积分'],
            ['key' => 'bronze_discount', 'value' => '1.0', 'description' => '青铜会员折扣'],
            ['key' => 'silver_min', 'value' => '1000', 'description' => '白银会员最低积分'],
            ['key' => 'silver_discount', 'value' => '0.98', 'description' => '白银会员折扣'],
            ['key' => 'gold_min', 'value' => '5000', 'description' => '黄金会员最低积分'],
            ['key' => 'gold_discount', 'value' => '0.95', 'description' => '黄金会员折扣'],
            ['key' => 'platinum_min', 'value' => '20000', 'description' => '铂金会员最低积分'],
            ['key' => 'platinum_discount', 'value' => '0.92', 'description' => '铂金会员折扣'],
            ['key' => 'diamond_min', 'value' => '50000', 'description' => '钻石会员最低积分'],
            ['key' => 'diamond_discount', 'value' => '0.88', 'description' => '钻石会员折扣'],
        ];

        foreach ($rules as $rule) {
            DB::table('points_rules')->insert($rule);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('points_rules');
    }
};
