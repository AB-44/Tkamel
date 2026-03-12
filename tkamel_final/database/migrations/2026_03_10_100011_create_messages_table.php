<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_user_id')->constrained('users')->onDelete('cascade');
            $table->string('receiver_type'); // 'user' or 'association'
            $table->unsignedBigInteger('receiver_id'); // polymorphic
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['receiver_type', 'receiver_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
