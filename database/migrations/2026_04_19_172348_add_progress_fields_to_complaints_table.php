<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasColumn('complaints', 'progress_note')) {
                $table->text('progress_note')->nullable()->after('remarks');
            }
            if (!Schema::hasColumn('complaints', 'progress_photos')) {
                $table->json('progress_photos')->nullable()->after('progress_note');
            }
            if (!Schema::hasColumn('complaints', 'progress_photo')) {
                $table->string('progress_photo')->nullable()->after('progress_photos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['progress_note', 'progress_photos', 'progress_photo']);
        });
    }
};