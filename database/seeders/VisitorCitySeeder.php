<?php

namespace Database\Seeders;

use App\Models\ShortUrl;
use App\Models\VisitorCity;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VisitorCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shortUrlIds =  ShortUrl::pluck('id')->all();

        VisitorCity::factory()
            ->count(10000)
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
