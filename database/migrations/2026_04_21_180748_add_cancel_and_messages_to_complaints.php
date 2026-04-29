<?php
// database/migrations/xxxx_add_cancel_and_messages_to_complaints.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable()->after('progress_updates');
        });

        Schema::create('complaint_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->string('sender_role'); // 'admin' or 'resident'
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('cancellation_reason');
        });
        Schema::dropIfExists('complaint_messages');
    }
};