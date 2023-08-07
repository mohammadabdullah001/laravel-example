<?php

namespace App\Jobs;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;

class GreetingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $greeting;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;


    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addSecond(10);
    }

    /**
     * Create a new job instance.
     */
    public function __construct($greeting)
    {
        $this->greeting = $greeting;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        info('handle start:' . $this->greeting);
        $records = [1, 2, 3, 4, 5, 6, 7];

        foreach ($records as $key => $value) {
            sleep(10);
            $array[$key] = $value;
            info('Sleep: ' . $this->greeting . '-' . $value);
        }

        info($array);
        info('handle end:' . $this->greeting);
    }
}
