<?php

namespace Database\Seeders;

use App\Models\ShortUrl;
use Illuminate\Support\Arr;
use App\Models\VisitorCount;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VisitorCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $shortUrlIds =  ShortUrl::pluck('id')->all();

        VisitorCount::factory()
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
