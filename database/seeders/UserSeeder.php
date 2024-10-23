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
            'name' => 'Milton Rodriguez',
            'email' => 'milton@gmail.com',
            'password' => 'adminadmin',
            'password' => Hash::make('jose'),
            'remember_token' => Str::random(10),
        ]);

    }
}
