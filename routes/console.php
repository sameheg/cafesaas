<?php

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (Command $command): void {
    $command->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
