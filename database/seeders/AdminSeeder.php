<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Lukeny',
            'email' => 'lukenyantonio11@gmail.com',
            'password' => Hash::make('nguevela@lukeny@barsal#'),
        ]);
    }
}