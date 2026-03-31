<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL: alter the enum to include 'review'
        // This is safe to run even if the column already has the right values
        try {
            DB::statement("ALTER TABLE associations MODIFY COLUMN status ENUM('pending','approved','rejected','review') NOT NULL DEFAULT 'pending'");
        } catch (\Exception $e) {
            // SQLite doesn't support ALTER COLUMN — silently skip (SQLite stores as text anyway)
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE associations MODIFY COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'");
        } catch (\Exception $e) {}
    }
};
