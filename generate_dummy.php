<?php

use Illuminate\Support\Facades\DB;

try {
    $a1Id = DB::table('associations')->insertGetId([
        'association_name' => 'عبدالله بشير',
        'email' => 'الاتحاد@tkamel.sa',
        'password_hash' => bcrypt('password'),
        'phone' => '111111111' . rand(100,999),
        'license_number' => '111' . rand(100,999),
        'category' => 'خيرية',
        'manager_name' => 'مدير 1',
        'status' => 'approved',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $a2Id = DB::table('associations')->insertGetId([
        'association_name' => 'عطيه',
        'email' => 'برشلونة@tkamel.sa',
        'password_hash' => bcrypt('password'),
        'phone' => '222222222' . rand(100,999),
        'license_number' => '222' . rand(100,999),
        'category' => 'خيرية',
        'manager_name' => 'مدير 2',
        'status' => 'approved',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('service_requests')->insert([
        'association_id' => $a1Id,
        'service_type' => 'training',
        'title' => 'تجربة',
        'details' => 'تفاصيل تجربة',
        'status' => 'processing',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('service_requests')->insert([
        'association_id' => $a2Id,
        'service_type' => 'initiatives',
        'title' => 'smt',
        'details' => 'تفاصيل المبادرة',
        'status' => 'processing',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    echo "Inserted without Eloquent successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
