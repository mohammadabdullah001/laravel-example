<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\LazyCollection;

class UserObserver
{

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        info('created');
        info($user);

        $redisUsers = Cache::store('redis')->get('users', []);

        $users = collect($redisUsers)
            ->prepend($user)
            ->sortDesc()
            ->values();

        Cache::store('redis')->put('users', $users);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        info('updated');
        info($user);

        $redisUsers = Cache::store('redis')->get('users', []);

        $lazyCollection = LazyCollection::make(function () use ($redisUsers) {
            foreach ($redisUsers as $user) {
                yield $user;
            }
        });

        $filteredCollection = collect($lazyCollection)
            ->chunk(2)
            ->flatten(1)
            ->map(function ($item) use ($user) {
                if ($item['id'] == $user->id) {
                    $item = $user;
                }

                return $item;
            })
            ->sortDesc()
            ->values();


        Cache::store('redis')->put('users', $filteredCollection);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        info('deleted');
        info($user);

        try {
            $redisUsers = Cache::store('redis')->get('users', []);

            $lazyCollection = LazyCollection::make(function () use ($redisUsers) {
                foreach ($redisUsers as $user) {
                    yield $user;
                }
            });

            $filteredCollection = collect($lazyCollection)
                ->chunk(2)
                ->flatten(1)
                ->reject(function ($item) use ($user) {
                    return $item['id'] === $user->id;
                })
                ->sortDesc()
                ->values();

            Cache::store('redis')->put('users', $filteredCollection);
        } catch (\Throwable $th) {
            info($th);
        }
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
