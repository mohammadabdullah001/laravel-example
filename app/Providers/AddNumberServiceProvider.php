<?php

namespace App\Providers;

use App\Action\AddNumberAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class AddNumberServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        app()->bind(AddNumberAction::class, function () {
            return new AddNumberAction;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [AddNumberAction::class];
    }
}
