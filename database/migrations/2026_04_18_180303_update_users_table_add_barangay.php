<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Palitan ang 'location' ng 'barangay'
            if (Schema::hasColumn('users', 'location') && !Schema::hasColumn('users', 'barangay')) {
                $table->renameColumn('location', 'barangay');
            }

            // Dagdag ng 'barangay' kung wala pang 'location' at 'barangay'
            if (!Schema::hasColumn('users', 'barangay') && !Schema::hasColumn('users', 'location')) {
                $table->string('barangay')->nullable()->after('phone');
            }

            // Siguraduhin may 'phone' column
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            // Siguraduhin may 'role' column
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'resident'])->default('resident')->after('barangay');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'barangay') && !Schema::hasColumn('users', 'location')) {
                $table->renameColumn('barangay', 'location');
            }
        });
    }
};