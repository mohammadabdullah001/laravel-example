<?php

namespace Database\Seeders;

use App\Models\ShortUrl;
use Illuminate\Support\Arr;
use App\Models\VisitorCountry;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VisitorCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $shortUrlIds =  ShortUrl::pluck('id')->all();

        VisitorCountry::factory()
            ->count(100000)
            ->create()
            ->each(function ($model) use ($shortUrlIds) {
                $model->update(
                    [
                        'short_url_id' =>  Arr::random($shortUrlIds),
                    ]
                );
            });
    }
}
