<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name','email','email_verified_at', 'password', 'remember_token']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('id')->nullable();
            $table->string('full_name')->after('role_id');
            $table->string('email')->unique()->after('full_name');
            $table->string('password_hash')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. حذف الأعمدة الجديدة
            $table->dropColumn(['role_id', 'full_name', 'password_hash']);
        });
        Schema::table('users', function (Blueprint $table) {
        // 2. إعادة الأعمدة الأصلية التي حذفناها في up
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
    });
    }
};
