<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(1000)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make(123456),
        // ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'customer',
        //     'email' => 'customer@example.com',
        //     'password' => Hash::make(123456),
        // ]);

        // $this->call([
        //     DesignationSeeder::class,
        //     UserSeeder::class,
        // ]);

        $this->call([
            UserSeeder::class,
            DepartmentSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
