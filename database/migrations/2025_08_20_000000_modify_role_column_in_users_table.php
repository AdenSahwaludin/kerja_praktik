<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
 /**
  * Run the migrations.
  */
 public function up(): void
 {
  // Ensure correct enum values for role column
  DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','manager','kasir') NOT NULL DEFAULT 'kasir';");
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  // Revert role column to string
  Schema::table('users', function (Blueprint $table) {
   $table->string('role')->default('kasir')->change();
  });
 }
};
