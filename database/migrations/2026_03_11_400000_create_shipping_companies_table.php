<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_companies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('物流公司代码');
            $table->string('name')->comment('物流公司名称');
            $table->string('website')->nullable()->comment('官网');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
        });

        $companies = [
            ['code' => 'sf', 'name' => '顺丰速运', 'website' => 'https://www.sf-express.com', 'sort' => 1],
            ['code' => 'yt', 'name' => '圆通速递', 'website' => 'https://www.yto.net.cn', 'sort' => 2],
            ['code' => 'yd', 'name' => '韵达速递', 'website' => 'https://www.yundaex.com', 'sort' => 3],
            ['code' => 'zt', 'name' => '中通快递', 'website' => 'https://www.zto.com', 'sort' => 4],
            ['code' => 'bs', 'name' => '百世快递', 'website' => 'https://www.bestexpress.com.cn', 'sort' => 5],
            ['code' => 'ems', 'name' => 'EMS', 'website' => 'http://www.ems.com.cn', 'sort' => 6],
            ['code' => 'jd', 'name' => '京东物流', 'website' => 'https://www.jd.com', 'sort' => 7],
        ];

        foreach ($companies as $company) {
            DB::table('shipping_companies')->insert($company);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_companies');
    }
};
