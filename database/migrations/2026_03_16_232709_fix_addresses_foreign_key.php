<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE addresses DROP FOREIGN KEY addresses_user_id_foreign');
        DB::statement('ALTER TABLE addresses CHANGE COLUMN customer_id customer_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE addresses ADD CONSTRAINT addresses_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE addresses DROP FOREIGN KEY addresses_customer_id_foreign');
        DB::statement('ALTER TABLE addresses CHANGE COLUMN customer_id customer_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE addresses ADD CONSTRAINT addresses_user_id_foreign FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE');
    }
};
