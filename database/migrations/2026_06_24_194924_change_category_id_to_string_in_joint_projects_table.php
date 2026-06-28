<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('joint_projects', function (Blueprint $table) {
            // Drop foreign key first
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['category_id']);
            }
        });

        Schema::table('joint_projects', function (Blueprint $table) {
            // Change column type to string to support multiple categories
            $table->string('category_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joint_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign('category_id')->references('id')->on('association_categories')->onDelete('set null');
            }
        });
    }
};
