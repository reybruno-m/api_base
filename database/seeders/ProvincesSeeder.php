<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = Storage::get('seeders/provinces.csv');

        $aryProvinces = explode("\n", $provinces);

        if(is_array($aryProvinces)){
            foreach ($aryProvinces as $province) {
                DB::table('provinces')->insert([
                    "name" => $province,
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }
        }
    }
}
