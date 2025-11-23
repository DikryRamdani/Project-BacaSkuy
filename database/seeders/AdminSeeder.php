<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@bacaskuy.com'],
            [
                'name' => 'Admin BacaSkuy',
                'email' => 'admin@bacaskuy.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        $this->command->info('✓ Admin user created!');
        $this->command->info('  Email: admin@bacaskuy.com');
        $this->command->info('  Password: admin123');
    }
}
