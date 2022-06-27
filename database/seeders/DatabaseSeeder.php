<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(3)->create();

        $companies = collect($this->makeCompaniesData());

        $companies->map(function ($item) {
            Company::factory(1)->create($item);
        });
    }

    private function makeCompaniesData(int $count = 10): array
    {
        $data = [];
        for ($i = 0; $i < $count; $i ++)
        {
            $data[] = ["user_id" => rand(1,3)];
        }

        return $data;
    }
}
