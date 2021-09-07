<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'test1',
            'email' => 'test1@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now()->timestamp,
        ]);
        User::factory()->create([
            'name' => 'test2',
            'email' => 'test2@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now()->timestamp,
        ]);
    }
}
