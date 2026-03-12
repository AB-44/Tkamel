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
            $table->string('title');
            $table->string('main_speaker')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date_time');
            $table->enum('meeting_type', ['online', 'in_person', 'hybrid'])->default('in_person');
            $table->enum('direction', ['local', 'international', 'both'])->default('local');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
