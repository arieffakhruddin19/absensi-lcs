<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create('/api/dashboard/stats', 'GET', ['month' => 7, 'year' => 2026]);

$controller = new \App\Http\Controllers\Admin\DashboardController();
$reflection = new \ReflectionClass($controller);
$method = $reflection->getMethod('getDashboardData');
$method->setAccessible(true);
$result = $method->invokeArgs($controller, [$request]);

print_r($result);
