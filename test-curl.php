<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// create a dummy request
$user = App\Models\User::first();

// Fake HTTP request exactly like frontend
$request = Illuminate\Http\Request::create('/user/service-requests', 'POST', [
    'service_type' => 'units',
    'title' => 'بسبس',
    'details' => 'سيس[',
    'preferred_date' => '2026-02-04',
    'budget' => '3'
]);

$request->headers->set('Accept', 'application/json');

// Login the user to bypass middleware auth
$app->make('auth')->login($user);

$response = $kernel->handle($request);
echo "Response Status: " . $response->getStatusCode() . "\n";
echo "Response Content: " . $response->getContent() . "\n";
