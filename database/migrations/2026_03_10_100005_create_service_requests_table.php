<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->constrained('associations')->onDelete('cascade');
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('service_type');
            $table->string('title');
            $table->text('details')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->date('preferred_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
