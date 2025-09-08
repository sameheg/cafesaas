<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS tenant_user_counts');

        DB::statement(<<<'SQL'
CREATE VIEW tenant_user_counts AS
select tenant_id, count(*) as user_count from users group by tenant_id
SQL);
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS tenant_user_counts');
    }
};
