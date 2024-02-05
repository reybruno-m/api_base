<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaidMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paid_methods = [
            'Contado',
            'Cuenta Corriente',
            'Debito',
            'Transferencia',
            'Cheque',
            'Otro',
        ];

        foreach ($paid_methods as $row) {
            DB::table('paid_methods')->insert([
                "name" => $row,
                "created_at" => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
