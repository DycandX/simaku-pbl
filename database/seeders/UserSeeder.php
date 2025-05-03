<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'staff', 'mahasiswa'];
        $now = Carbon::now();

        for ($i = 1; $i <= 12; $i++) {
            DB::table('users')->insert([
                'username' => '4.33.2.3.' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'password' => Hash::make('12345678'),
                'email' => 'user' . $i . '@example.com',
                'role' => $roles[array_rand($roles)],
                'is_active' => rand(0, 1),
                'last_login' => rand(0, 1) ? $now : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
