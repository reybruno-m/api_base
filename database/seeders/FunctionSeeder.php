<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('functions')->insert([
            "name" => "Inicio",
            "description" => "Funcion basica para todos los usuarios.",
            "url" => "home",
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
