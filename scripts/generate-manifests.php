<?php

use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';

$privateKey = getenv('MODULE_PRIVATE_KEY');
if (! $privateKey) {
    fwrite(STDERR, "MODULE_PRIVATE_KEY env var not set" . PHP_EOL);
    exit(1);
}
$privateKey = base64_decode($privateKey);

$modulesFile = __DIR__ . '/../modules.json';
$data = json_decode(file_get_contents($modulesFile), true);

foreach ($data['modules'] as $module) {
    $key = $module['key'];
    $studly = Str::studly($key);
    $moduleDir = __DIR__ . '/../Modules/' . $studly;

    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($moduleDir));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $files[] = $file->getPathname();
        }
    }
    sort($files);
    $hashCtx = hash_init('sha256');
    foreach ($files as $file) {
        hash_update($hashCtx, file_get_contents($file));
    }
    $checksum = hash_final($hashCtx);
    $signature = base64_encode(sodium_crypto_sign_detached($checksum, $privateKey));

    $manifest = [
        'name' => $key,
        'description' => ucfirst($key) . ' module',
        'version' => '1.0.0',
        'capabilities' => [],
        'dependencies' => [],
        'paths' => [],
        'events' => [],
        'roles' => [],
        'checksum' => $checksum,
        'signature' => $signature,
    ];

    $targetDir = __DIR__ . '/../modules/' . $key;
    if (! is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    file_put_contents($targetDir . '/module.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);
}
