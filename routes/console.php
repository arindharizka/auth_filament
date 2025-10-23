<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule dummy post processing command
Schedule::command('posts:process')->everyMinute();
Schedule::command('instagram:fetch-metrics')->everyMinute(); 
