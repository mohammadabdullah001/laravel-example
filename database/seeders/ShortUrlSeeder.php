<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\ShortUrl;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShortUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaign_ids =  Campaign::pluck('id')->all();

        ShortUrl::factory()
            ->count(100000)
            ->create()
            ->each(function ($model) use ($campaign_ids) {
                $model->update(
                    [
                        'campaign_id' =>  Arr::random($campaign_ids),
                    ]
                );
            });
    }
}
