<?php

namespace App\Console\Commands;

use App\Jobs\GreetingJob;
use Illuminate\Console\Command;

class RunGreeting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-greeting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Greeting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $greetings = [
            'Hi',
            'Hello',
            'Salaam',
        ];

        foreach ($greetings as $greeting) {
            GreetingJob::dispatch($greeting);
        }
    }
}
