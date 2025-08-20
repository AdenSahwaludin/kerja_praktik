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
   // Rename default password column to kata_sandi
   if (Schema::hasColumn('users', 'password')) {
    $table->renameColumn('password', 'kata_sandi');
   }

   // Add telepon column
   if (!Schema::hasColumn('users', 'telepon')) {
    $table->string('telepon')->nullable()->after('email');
   }

   // Add role column
   if (!Schema::hasColumn('users', 'role')) {
    $table->enum('role', ['admin', 'manager', 'kasir'])->default('kasir')->after('telepon');
   }

   // Add terakhir_login column
   if (!Schema::hasColumn('users', 'terakhir_login')) {
    $table->timestamp('terakhir_login')->nullable()->after('role');
   }
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::table('users', function (Blueprint $table) {
   if (Schema::hasColumn('users', 'terakhir_login')) {
    $table->dropColumn('terakhir_login');
   }
   if (Schema::hasColumn('users', 'role')) {
    $table->dropColumn('role');
   }
   if (Schema::hasColumn('users', 'telepon')) {
    $table->dropColumn('telepon');
   }
   if (Schema::hasColumn('users', 'kata_sandi')) {
    $table->renameColumn('kata_sandi', 'password');
   }
  });
 }
};
