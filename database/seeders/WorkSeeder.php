<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Work')->insert([
            [
                'title' => 'Software Developer',
                'description' => 'Develop and maintain web applications.',
                'location' => 'Casa Blanca',
                'type' => 'Online',
                'category' => 'IT',
                'status' => 'Open',
                'salary' => 60000,
                'email' => 'developer@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Graphic Designer',
                'description' => 'Create visual designs for digital platforms.',
                'location' => 'Agadir',
                'type' => 'In-Person',
                'category' => 'Design',
                'status' => 'Open',
                'salary' => 50000,
                'email' => 'designer@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
