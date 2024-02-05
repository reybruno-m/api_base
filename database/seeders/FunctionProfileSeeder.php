<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FunctionProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('functions_profiles')->insert([
            "function_id" => 1,
            "profile_id" => 1,
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
