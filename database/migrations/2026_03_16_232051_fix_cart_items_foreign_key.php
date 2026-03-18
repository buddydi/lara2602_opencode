<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE cart_items DROP FOREIGN KEY cart_items_user_id_foreign');
        DB::statement('ALTER TABLE cart_items CHANGE COLUMN customer_id customer_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE cart_items ADD CONSTRAINT cart_items_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE cart_items DROP FOREIGN KEY cart_items_customer_id_foreign');
        DB::statement('ALTER TABLE cart_items CHANGE COLUMN customer_id customer_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE cart_items ADD CONSTRAINT cart_items_user_id_foreign FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE');
    }
};
