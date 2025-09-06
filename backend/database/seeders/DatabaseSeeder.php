<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user and roles first
        $this->call([
            AdminUserSeeder::class,
        ]);
        
        // Then seed RBAC data
        $this->call([
            RBACSeeder::class,
        ]);
        
        // Then seed other data
        $this->call([
            MenuItemsTableSeeder::class,
            TemplatesTableSeeder::class,
            CompanyAndVacancySeeder::class
        ]);
        
        // Create test user (uncomment if needed)
        // $user = User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => Hash::make('password'),
        // ]);
    }
}
