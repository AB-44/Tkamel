<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            if (!Schema::hasColumn('meetings', 'category')) {
                $table->string('category')->nullable()->after('direction');
            }
            if (!Schema::hasColumn('meetings', 'presenter')) {
                $table->string('presenter')->nullable()->after('category');
            }
            if (!Schema::hasColumn('meetings', 'date')) {
                $table->date('date')->nullable()->after('presenter');
            }
            if (!Schema::hasColumn('meetings', 'time')) {
                $table->string('time', 5)->nullable()->after('date');
            }
            if (!Schema::hasColumn('meetings', 'type')) {
                $table->enum('type', ['online', 'onsite'])->nullable()->after('time');
            }
            if (!Schema::hasColumn('meetings', 'status')) {
                $table->enum('status', ['upcoming', 'past', 'cancelled'])->default('upcoming')->after('type');
            }
            if (!Schema::hasColumn('meetings', 'link')) {
                $table->text('link')->nullable()->after('status');
            }
            if (!Schema::hasColumn('meetings', 'location')) {
                $table->string('location')->nullable()->after('link');
            }
            if (!Schema::hasColumn('meetings', 'notes')) {
                $table->text('notes')->nullable()->after('location');
            }
            if (!Schema::hasColumn('meetings', 'report_summary')) {
                $table->text('report_summary')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('meetings', 'report_decisions')) {
                $table->text('report_decisions')->nullable()->after('report_summary');
            }
            if (!Schema::hasColumn('meetings', 'report_attendees')) {
                $table->unsignedInteger('report_attendees')->nullable()->after('report_decisions');
            }
            if (!Schema::hasColumn('meetings', 'report_actions')) {
                $table->text('report_actions')->nullable()->after('report_attendees');
            }
            if (!Schema::hasColumn('meetings', 'cancel_reason')) {
                $table->text('cancel_reason')->nullable()->after('report_actions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $drop = [
                'category',
                'presenter',
                'date',
                'time',
                'type',
                'status',
                'link',
                'location',
                'notes',
                'report_summary',
                'report_decisions',
                'report_attendees',
                'report_actions',
                'cancel_reason',
            ];

            $existing = array_values(array_filter($drop, fn ($col) => Schema::hasColumn('meetings', $col)));
            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};

