<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing User\MeetingController@index...\n";
try {
    $req = Illuminate\Http\Request::create('/user/meetings', 'GET');
    $user = App\Models\User::first();
    $req->setUserResolver(function() use ($user) { return $user; });
    app(App\Http\Controllers\User\MeetingController::class)->index($req);
    echo "User\MeetingController@index OK\n";
} catch (\Exception $e) {
    echo "ERROR in User\MeetingController: " . $e->getMessage() . "\n";
}

echo "Testing Admin\MeetingController@userIndex...\n";
try {
    $req = Illuminate\Http\Request::create('/admin/meetings/user-index', 'GET');
    $user = App\Models\User::first();
    $req->setUserResolver(function() use ($user) { return $user; });
    app(App\Http\Controllers\Admin\MeetingController::class)->userIndex();
    echo "Admin\MeetingController@userIndex OK\n";
} catch (\Exception $e) {
    echo "ERROR in Admin\MeetingController: " . $e->getMessage() . "\n";
}
