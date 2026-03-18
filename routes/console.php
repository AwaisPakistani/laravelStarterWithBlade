<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\{Artisan, Schedule};
use App\Console\Commands\SendEmailsCommand;

Schedule::command('queue:work redis --queue=exports --tries=3 --sleep=3')
    ->everyTwoSeconds()
    ->withoutOverlapping()
    ->runInBackground();



Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Schedule an Artisan command
// Schedule::command('emails:send Taylor --force')->daily();

// Schedule an Artisan command using its class name
// Schedule::command(SendEmailsCommand::class, ['Taylor', '--force'])->daily();

// Schedule a queued job
// Schedule::job(new ProcessExportJob)->everyMinute();
// You need to pass the required arguments


// Schedule a callable/closure
// Schedule::call(function () {
//     \Log::info('This runs every minute!');
// })->everyMinute();

// Schedule a shell command to the OS
// Schedule::exec('node /home/forge/script.js')->daily();
