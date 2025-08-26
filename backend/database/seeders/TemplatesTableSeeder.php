<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there is an admin user to assign as author
        $admin = User::query()->where('role', 'admin')->first();
        if (! $admin) {
            $admin = User::query()->firstOrCreate(
                ['email' => 'admin@example2.com'],
                [
                    'name' => 'Admin User',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]
            );
        }

        $templates = [
            [
                'name' => 'Professional',
                'key' => 'professional',
                'description' => 'Clean and professional resume template with a modern layout.',
                'category' => 'professional',
                'version' => '1.0.0',
                'thumbnail' => 'https://placehold.co/400x500/2563eb/ffffff?text=Professional',
                'content' => '<div class="resume"><h1>{{ resume.title }}</h1></div>',
                'is_active' => true,
                'is_public' => true,
                'is_premium' => false,
                'price' => 0,
            ],
            [
                'name' => 'Modern',
                'key' => 'modern',
                'description' => 'Sleek and contemporary design with creative elements.',
                'category' => 'modern',
                'version' => '1.1.0',
                'thumbnail' => 'https://placehold.co/400x500/7c3aed/ffffff?text=Modern',
                'content' => '<section class="header"><h1>{{ user.first_name }} {{ user.last_name }}</h1></section>',
                'is_active' => true,
                'is_public' => true,
                'is_premium' => true,
                'price' => 9.99,
            ],
            [
                'name' => 'Minimal',
                'key' => 'minimal',
                'description' => 'Simple and clean layout with focus on content.',
                'category' => 'minimal',
                'version' => '1.2.0',
                'thumbnail' => 'https://placehold.co/400x500/059669/ffffff?text=Minimal',
                'content' => '<main><p>{{ resume.summary }}</p></main>',
                'is_active' => true,
                'is_public' => true,
                'is_premium' => false,
                'price' => 0,
            ],
            [
                'name' => 'Creative',
                'key' => 'creative',
                'description' => 'Bold and artistic design for standout resumes.',
                'category' => 'creative',
                'version' => '1.0.0',
                'thumbnail' => 'https://placehold.co/400x500/d97706/ffffff?text=Creative',
                'content' => '<div class="creative">{{ resume.content }}</div>',
                'is_active' => true,
                'is_public' => false,
                'is_premium' => true,
                'price' => 14.99,
            ],
        ];

        foreach ($templates as $data) {
            Template::query()->firstOrCreate(
                ['key' => $data['key']],
                $data + ['author_id' => $admin->id]
            );
        }
    }
}
