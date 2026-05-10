<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            // Core info
            $table->string('title');
            $table->string('main_speaker')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date_time');
            $table->enum('meeting_type', ['online', 'in_person', 'hybrid'])->default('in_person');
            $table->enum('direction', ['local', 'international', 'both'])->default('local');

            // Admin module fields
            $table->string('category')->nullable();
            $table->string('presenter')->nullable();
            $table->date('date')->nullable();
            $table->string('time', 5)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->enum('type', ['online', 'onsite'])->nullable();
            $table->enum('status', ['upcoming', 'past', 'cancelled'])->default('upcoming');
            $table->string('invitation_direction')->nullable();
            $table->text('link')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();

            // Report fields
            $table->text('report_summary')->nullable();
            $table->text('report_decisions')->nullable();
            $table->unsignedInteger('report_attendees')->nullable();
            $table->text('report_actions')->nullable();
            $table->text('cancel_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
