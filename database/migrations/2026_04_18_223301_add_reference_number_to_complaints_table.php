<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasColumn('complaints', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('complaints', 'assigned_officer')) {
                $table->string('assigned_officer')->nullable()->after('status');
            }
            if (!Schema::hasColumn('complaints', 'remarks')) {
                $table->text('remarks')->nullable()->after('assigned_officer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['reference_number', 'assigned_officer', 'remarks']);
        });
    }
};