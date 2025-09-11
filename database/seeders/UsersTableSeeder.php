<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role' => '0',
            'nrp' => '250504',
            'kontak' => '085348218391',
            'nama' => 'Bayu Rezky',
            'password' => bcrypt('250504'),
        ]);
        
    }
}
