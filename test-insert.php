<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $sr = App\Models\ServiceRequest::create([
        'user_id' => 1,
        'service_type' => 'units',
        'title' => 'Test',
        'details' => 'Test details',
        'budget' => 3,
        'preferred_date' => '2026-02-04',
        'status' => 'pending'
    ]);
    echo "Success: " . $sr->id . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
