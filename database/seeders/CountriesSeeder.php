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
                list($nombre, $name, $nom, $iso2, $iso3, $phone_code) = explode(",", $country);

                DB::table('countries')->insert([
                    "nombre" => $nombre,
                    "name" => $name,
                    "nom" => $nom,
                    "iso2" => $iso2,
                    "iso3" => $iso3,
                    "phone_code" => $phone_code,
                    "created_at" => date("Y-m-d H:i:s"),
                ]);
            }
        }
    }
}
