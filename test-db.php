<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $sr = App\Models\ServiceRequest::create([
        'user_id' => \App\Models\User::first()->id ?? null,
        'service_type' => 'units',
        'title' => 'Test',
        'details' => 'Test details',
        'budget' => 3,
        'preferred_date' => '2026-02-04',
        'status' => 'pending'
    ]);
    file_put_contents('test-db-output.txt', "Success ID: " . $sr->id);
} catch (\Exception $e) {
    file_put_contents('test-db-output.txt', "Exception: " . $e->getMessage());
}
