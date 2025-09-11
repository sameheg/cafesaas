<?php

namespace Tests;

use Illuminate\Foundation\Application;

trait CreatesApplication
{
    public function createApplication(): Application
    {
        $basePath = dirname(__DIR__);

        if (! file_exists($basePath.'/.env') && file_exists($basePath.'/.env.example')) {
            copy($basePath.'/.env.example', $basePath.'/.env');
        }

        $app = require $basePath.'/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
