<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'login' => 'admin',
            'password' => 'admin',
            'is_admin' => 1,
        ]);

         User::factory()->create([
            'login' => 'user',
            'password' => 'user',
            'is_admin' => 0,
        ]);
    }
}
