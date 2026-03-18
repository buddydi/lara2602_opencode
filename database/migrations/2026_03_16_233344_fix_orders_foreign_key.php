<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement('ALTER TABLE orders DROP FOREIGN KEY orders_user_id_foreign');
        } catch (\Exception $e) {
            // 可能已经不存在
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE orders ADD CONSTRAINT orders_user_id_foreign FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE');
    }
};
