<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD');
        if (! $password) {
            throw new RuntimeException('ADMIN_PASSWORD must be set before seeding the production admin.');
        }

        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@ricevibe.in')],
            [
                'name' => env('ADMIN_NAME', 'RiceVibe Admin'),
                'password' => Hash::make($password),
            ]
        );
    }
}
