<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('association_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->default('🏢');
            $table->string('color')->default('#2ab8d0');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default categories
        $defaults = [
            ['name' => 'خيرية', 'icon' => '❤️', 'color' => '#f85149', 'description' => 'جمعيات العمل الخيري والاجتماعي'],
            ['name' => 'تعليمية', 'icon' => '🎓', 'color' => '#1f6feb', 'description' => 'جمعيات التعليم والتدريب'],
            ['name' => 'بيئية', 'icon' => '🌿', 'color' => '#3fb950', 'description' => 'جمعيات حماية البيئة'],
            ['name' => 'صحية', 'icon' => '🏥', 'color' => '#58a6ff', 'description' => 'جمعيات الرعاية الصحية'],
            ['name' => 'رياضية', 'icon' => '⚽', 'color' => '#bc8cff', 'description' => 'جمعيات الأنشطة الرياضية'],
            ['name' => 'ثقافية', 'icon' => '🎭', 'color' => '#e3b341', 'description' => 'جمعيات الثقافة والفنون'],
        ];

        foreach ($defaults as $cat) {
            \DB::table('association_categories')->insert(array_merge($cat, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('association_categories');
    }
};
