<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

Schedule::command('bookings:send-reminders')->daily();
Schedule::command('bookings:cancel-stale')->everyMinute();
Schedule::command('bookings:auto-complete')->daily();
Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();

