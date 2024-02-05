<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $countries = Storage::get('seeders/countries.csv');

        $aryCountries = explode("\n", $countries);

        if(is_array($aryCountries)){
            foreach ($aryCountries as $country) {
                list($iso, $name) = explode(",", $country);

                DB::table('countries')->insert([
                    "name" => $name,
                    "iso" => $iso,
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }
        }
    }
}
