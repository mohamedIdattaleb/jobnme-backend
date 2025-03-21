<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'mohamed@gmail.com',
                'phone' => '0723456789', 
                'nationality' => 'Morocco', 
                'gender' => 'male', 
                'date_of_birth' => '2003-02-28', 
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ],
            [
                'name' => 'mjid',
                'email' => 'mjid@gmail.com',
                'phone' => '0623456790',
                'nationality' => 'Morocco',
                'gender' => 'male',
                'date_of_birth' => '2005-05-30',
                'password' => Hash::make('12340000'),
                'role' => 'job_seeker',
            ],
            [
                'name' => 'mohamed',
                'email' => 'moh@gmail.com',
                'phone' => '0623456791',
                'nationality' => 'Morocco',
                'gender' => 'male',
                'date_of_birth' => '2004-02-07',
                'password' => Hash::make('00001234'),
                'role' => 'employer',
            ],
        ]);
    }
}
