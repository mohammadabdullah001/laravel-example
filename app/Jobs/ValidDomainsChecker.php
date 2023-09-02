<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValidDomainsChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('greeting');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {

            collect([
                'https://exampletest.com/',
                'https://test.com',
                'https://www.facebook.com',
                'https://www.prothomalo.com',
            ])
                ->each(function ($url) {
                    try {
                        $responses = Http::get($url);

                        if ($responses->successful()) {
                            $message = 'success';
                            $getMessage = '';
                        }
                    } catch (\Throwable $th) {
                        $message = 'error';
                        $getMessage = $th->getMessage();
                        Log::channel('custom')->error($getMessage);
                    }

                    Log::channel('custom')->info("{$message}: {$url} {$getMessage}");
                });
        } catch (\Throwable $th) {
            Log::channel('custom')->error($th);
        }
    }
}
