<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hash1 = '$2y$10$cbN4PnBVEo7UniEt2BRuiOTwBc54adnpb5RXeJ19khiw2e.IJvNAi';
$hash2 = '$2y$10$to4ehBQEVJqM5qvwZBeAM.Kg0tyxtV2szypyRtcSyKSpCbzfkXyzC';

echo "Hash 1: " . (Illuminate\Support\Facades\Hash::check('123456789', $hash1) ? 'OK' : 'FAIL') . "\n";
echo "Hash 2: " . (Illuminate\Support\Facades\Hash::check('123456789', $hash2) ? 'OK' : 'FAIL') . "\n";
