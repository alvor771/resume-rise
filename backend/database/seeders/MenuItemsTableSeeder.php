<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the CV-Swap menu item already exists
        $existingItem = MenuItem::where('path', '/cv-swap')->first();
        
        if (!$existingItem) {
            // Create the CV-Swap parent menu item
            $cvSwap = MenuItem::create([
                'title' => 'CV Swap',
                'path' => '/cv-swap',
                'icon' => 'swap-horizontal',
                'order' => 3, // Adjust the order as needed
                'is_visible' => true,
                'is_guest' => false,
                'is_auth' => true,
                'permissions' => json_encode(['view cv-swap']),
            ]);

            // Create submenu items
            $subItems = [
                [
                    'title' => 'Discover',
                    'path' => '/cv-swap/discover',
                    'icon' => 'search',
                    'order' => 1,
                    'is_visible' => true,
                    'is_guest' => false,
                    'is_auth' => true,
                    'permissions' => json_encode(['view cv-swap']),
                ],
                [
                    'title' => 'Matches',
                    'path' => '/cv-swap/matches',
                    'icon' => 'heart',
                    'order' => 2,
                    'is_visible' => true,
                    'is_guest' => false,
                    'is_auth' => true,
                    'permissions' => json_encode(['view cv-swap']),
                ],
                [
                    'title' => 'My Profile',
                    'path' => '/cv-swap/profile',
                    'icon' => 'user',
                    'order' => 3,
                    'is_visible' => true,
                    'is_guest' => false,
                    'is_auth' => true,
                    'permissions' => json_encode(['view cv-swap']),
                ],
                [
                    'title' => 'Company CV',
                    'path' => '/cv-swap/company',
                    'icon' => 'building-office',
                    'order' => 4,
                    'is_visible' => true,
                    'is_guest' => false,
                    'is_auth' => true,
                    'permissions' => json_encode(['manage company profile']),
                ],
            ];

            foreach ($subItems as $subItem) {
                $cvSwap->children()->create($subItem);
            }
        }
    }
}
