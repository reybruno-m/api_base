<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            "username" => "admin",
            "password" => Hash::make("050918b."),
            'uuid' => (string) Str::uuid(),
            "last_name" => "Rey",
            "first_name" => "Bruno Martin",
            "email" => "brunomartinrey@gmail.com",
            "phone_number" => "3446638140",
            "profile_id" => 1,
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
