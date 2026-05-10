<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('joint_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['planning', 'active', 'idea', 'completed', 'canceled'])->default('planning');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('association_categories')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('joint_project_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->text('body');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('joint_projects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('joint_project_updates');
        Schema::dropIfExists('joint_projects');
    }
};
