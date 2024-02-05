<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class IVASeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $iva = [
            '-',
            'Responsable Inscripto',
            'Exento',
            'Consumidor Final',
            'Responsable Monotributo',
        ];

        foreach ($iva as $row) {
            DB::table('iva')->insert([
                "name" => $row,
                "created_at" => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
