<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:auth-module', function (): void {
    $this->info('Laravel Clean Auth Module is installed.');
})->purpose('Display auth module installation status.');
