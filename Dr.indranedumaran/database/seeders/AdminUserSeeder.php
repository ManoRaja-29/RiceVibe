<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        if (User::where('email', $email)->exists()) {
            return;
        }
        $password = env('ADMIN_PASSWORD') ?: Str::password(16, symbols: false);

        User::create([
            'name' => env('ADMIN_NAME', 'Site Admin'),
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->command?->warn("Admin created: {$email}");
        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn("Generated password (save it now, shown once): {$password}");
        }
    }
}
