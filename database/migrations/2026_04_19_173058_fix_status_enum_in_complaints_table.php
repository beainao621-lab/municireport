<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('complaints')->where('status', 'pending')->update(['status' => 'Pending']);
        DB::table('complaints')->where('status', 'in_progress')->update(['status' => 'In Progress']);
        DB::table('complaints')->where('status', 'resolved')->update(['status' => 'Resolved']);
        DB::table('complaints')->where('status', 'rejected')->update(['status' => 'Rejected']);

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::table('complaints', function (Blueprint $table) {
                $table->string('status')->default('Pending')->change();
            });
        } else {
            DB::statement("ALTER TABLE complaints MODIFY COLUMN status ENUM('Pending', 'In Progress', 'Resolved', 'Rejected') NOT NULL DEFAULT 'Pending'");
        }
    }

    public function down(): void
    {
        DB::table('complaints')->where('status', 'Pending')->update(['status' => 'pending']);
        DB::table('complaints')->where('status', 'In Progress')->update(['status' => 'in_progress']);
        DB::table('complaints')->where('status', 'Resolved')->update(['status' => 'resolved']);
        DB::table('complaints')->where('status', 'Rejected')->update(['status' => 'rejected']);

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::table('complaints', function (Blueprint $table) {
                $table->string('status')->default('pending')->change();
            });
        } else {
            DB::statement("ALTER TABLE complaints MODIFY COLUMN status ENUM('pending', 'in_progress', 'resolved', 'rejected') NOT NULL DEFAULT 'pending'");
        }
    }
};