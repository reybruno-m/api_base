<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = Storage::get('seeders/cities.csv');

        $aryCities = explode("\n", $cities);

        if(is_array($aryCities)){
            foreach ($aryCities as $city) {
                list($province_id, $name) = explode(",", $city);

                DB::table('cities')->insert([
                    "name" => $name,
                    "province_id" => $province_id,
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }
        }
    }
}
