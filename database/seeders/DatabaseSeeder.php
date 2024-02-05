<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            #IVASeeder::class,
            #StatesSeeder::class,
            #PaidMethodsSeeder::class,
            #CountriesSeeder::class,
            #ProvincesSeeder::class,
            #CitiesSeeder::class,
            #ProfileSeeder::class,
            #FunctionSeeder::class,
            FunctionProfileSeeder::class,
            UserSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
