public function up(): void
{
    Schema::table('complaints', function (Blueprint $table) {
        $table->string('reference_number')->nullable()->after('user_id');
        $table->string('assigned_officer')->nullable()->after('status');
        $table->text('remarks')->nullable()->after('assigned_officer');
    });
}

public function down(): void
{
    Schema::table('complaints', function (Blueprint $table) {
        $table->dropColumn(['reference_number', 'assigned_officer', 'remarks']);
    });
}