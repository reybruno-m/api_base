<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state = [
            'Abierto',
            'Cerrado',
            'En Curso',
            'Rechazado',
            'Impago',
            'Pago',
        ];

        foreach ($state as $row) {
            DB::table('states')->insert([
                "name" => $row,
                "created_at" => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
