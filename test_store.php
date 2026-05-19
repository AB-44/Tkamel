<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::first();
$req = Illuminate\Http\Request::create('/meetings', 'POST', [
    'title' => 'Test Agenda', 
    'category' => 'عام', 
    'presenter' => 'Test', 
    'date' => '2026-05-19', 
    'time' => '10:00', 
    'type' => 'online', 
    'invitation_direction' => 'عام',
    'agenda_items' => [
        ['title' => 'Topic 1', 'duration' => null, 'presenter' => null],
        ['title' => 'Topic 2', 'duration' => 20, 'presenter' => 'Me']
    ]
]);
$req->headers->set('Accept', 'application/json');
$req->setUserResolver(function() use ($user) { return $user; });

try {
    $meetings = App\Models\Meeting::all();
    foreach ($meetings as $m) {
        $targets = $m->targetAssociations;
        foreach ($targets as $t) {
            if (!is_object($t)) {
                echo "Meeting " . $m->id . " has string target: " . print_r($t, true) . "\n";
            }
        }
    }
    echo "Done.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
