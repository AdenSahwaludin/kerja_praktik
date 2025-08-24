<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Modify existing columns
            $table->renameColumn('name', "nama");
            $table->string('nama', 100)->change();
            $table->string('email', 50)->change();

            // Rename password to kata_sandi and set fixed length
            $table->renameColumn('password', 'kata_sandi');
            $table->char('kata_sandi', 60)->change();

            // Add new columns
            // Make telepon column nullable to allow missing phone numbers
            $table->string('telepon', 20)->nullable()->after('email');
            $table->enum('role', ['admin', 'kasir'])->after('kata_sandi');
            $table->datetime('terakhir_login')->nullable()->after('role');

            // Remove unused columns
            $table->dropColumn('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('nama', 'name');
            $table->string('name')->change();
            $table->string('email')->change();
            $table->renameColumn('kata_sandi', 'password');
            $table->string('password')->change();
            $table->dropColumn(['telepon', 'role', 'terakhir_login']);
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
