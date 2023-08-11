<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department_ids =  Department::pluck('id')->all();

        Employee::factory()
            ->count(10)
            ->create()
            ->each(function ($user) use ($department_ids) {
                $user->update(
                    [
                        'department_id' =>  Arr::random($department_ids),
                    ]
                );
            });
    }
}
