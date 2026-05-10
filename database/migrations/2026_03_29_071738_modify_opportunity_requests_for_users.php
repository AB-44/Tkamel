<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('opportunity_requests', function (Blueprint $table) {
            $table->dropForeign(['opportunity_id']);
            $table->dropForeign(['association_id']);
            $table->dropUnique(['opportunity_id', 'association_id']);
            $table->unsignedBigInteger('association_id')->nullable()->change();
            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('cascade');
            $table->foreign('association_id')->references('id')->on('associations')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('association_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunity_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            // Revert association_id to NOT NULL
            $table->dropForeign(['opportunity_id']);
            $table->dropForeign(['association_id']);
            $table->unsignedBigInteger('association_id')->nullable(false)->change();
            $table->unique(['opportunity_id', 'association_id']);
            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('cascade');
            $table->foreign('association_id')->references('id')->on('associations')->onDelete('cascade');
        });
    }
};
