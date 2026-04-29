<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // progress_updates JSON field na lang ang gagamitin natin para sa comments
        // Gagawa tayo ng bagong complaints_comments table
        DB::statement("
            CREATE TABLE complaint_comments (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                complaint_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                update_index INT NOT NULL COMMENT 'Index of progress_update this comment is for',
                comment TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS complaint_comments");
    }
};