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
    public $tries = 3;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;


    /**
     * Create a new job instance.
     */
    public function __construct($greeting)
    {
        $this->greeting = $greeting;

        $this->onQueue('greeting');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        info('start:' . $this->greeting);
        $records = [1, 2, 3, 4, 5, 6, 7, 8];

        foreach ($records as $key => $value) {
            sleep(60);
            $array[$key] = $value;
            info('Sleep: ' . $this->greeting . '-' . $value);
        }

        info($array);
        info('end:' . $this->greeting);
    }
}
