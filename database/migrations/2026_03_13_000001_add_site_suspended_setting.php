<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now('Asia/Jakarta');

        DB::table('settings')->updateOrInsert(
            ['key' => 'site_suspended'],
            ['value' => 0, 'updated_at' => $now, 'created_at' => $now]
        );
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'site_suspended')
            ->delete();
    }
};
