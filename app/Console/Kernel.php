<?php

namespace App\Console;

use App\Jobs\GreetingJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();


        $schedule->command('app:run-greeting')
            ->dailyAt('16:03')
            ->timezone('Asia/Dhaka');

        // $greeting = 'Hi';
        // $schedule->job(new GreetingJob($greeting))
        //     ->name('Greeting:' . $greeting)
        //     ->everyMinute()
        //     ->withoutOverlapping(10)
        //     ->onSuccess(function () use ($greeting) {
        //         info('Success: ' . $greeting);
        //     })
        //     ->onFailure(function () use ($greeting) {
        //         info('Failure: ' . $greeting);
        //     });


        // $greeting = 'Hello';
        // $schedule->job(new GreetingJob($greeting))
        //     ->name('Greeting:' . $greeting)
        //     ->everyMinute()
        //     ->withoutOverlapping(10)
        //     ->onSuccess(function () use ($greeting) {
        //         info('Success: ' . $greeting);
        //     })
        //     ->onFailure(function () use ($greeting) {
        //         info('Failure: ' . $greeting);
        //     });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
