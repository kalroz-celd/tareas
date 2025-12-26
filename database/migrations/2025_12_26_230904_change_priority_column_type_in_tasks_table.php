<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize existing numeric priorities to string values before altering the column type.
        DB::table('tasks')->where('priority', 1)->update(['priority' => 'high']);
        DB::table('tasks')->where('priority', 2)->update(['priority' => 'medium']);
        DB::table('tasks')->where('priority', 3)->update(['priority' => 'low']);

        DB::statement("ALTER TABLE tasks MODIFY priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium'");
    }

    public function down(): void
    {
        DB::table('tasks')->where('priority', 'high')->update(['priority' => 1]);
        DB::table('tasks')->where('priority', 'medium')->update(['priority' => 2]);
        DB::table('tasks')->where('priority', 'low')->update(['priority' => 3]);

        DB::statement('ALTER TABLE tasks MODIFY priority TINYINT UNSIGNED NOT NULL DEFAULT 2');
    }
};
