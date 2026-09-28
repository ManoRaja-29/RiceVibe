<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('ricevibe:status', function () {
    $this->info('RiceVibe Laravel application is ready.');
})->purpose('Check the RiceVibe Laravel application bootstrap');
