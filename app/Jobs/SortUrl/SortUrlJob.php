<?php

namespace App\Jobs\SortUrl;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SortUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $user;


    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $records = [1, 2, 3, 4, 5];

        foreach ($records as $key => $value) {
            sleep(10);
            $array[$key] = $value;
            info('Sleep: ' . $this->user['name']);
        }

        info($array);
    }

    public function failed(\Exception $e)
    {
        Log::error('Job failed: ' . $e->getMessage());
    }
}
