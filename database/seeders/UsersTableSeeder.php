<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'fullname' => 'Fayyadh Arrazan Miftakhul',
            'username' => 'fayyadh',
            'email' => 'fayyadh@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('rahasia'),
            'role' => 'admin',
            'photo' => 'bakwan_krispi.png',
        ]);

        // Tambahkan 4 baris data lainnya di sini

        User::factory()->count(5)->create();
    }
}
