<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => '管理者',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::create([
            'name' => '店舗管理者',
            'email' => 'shop@shop.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ])->assignRole('writer');

        User::create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        User::factory()->count(47)->create();
    }
}
