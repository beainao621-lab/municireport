<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // ← IDAGDAG ITO

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE complaints MODIFY COLUMN status ENUM('Pending','In Progress','Resolved','Cancelled') NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE complaints MODIFY COLUMN status ENUM('Pending','In Progress','Resolved') NOT NULL DEFAULT 'Pending'");
    }
};