<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User management
            [
                'name' => 'view_users', 
                'slug' => 'view-users', 
                'group' => 'users', 
                'description' => 'View user list and details',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'create_users', 
                'slug' => 'create-users', 
                'group' => 'users', 
                'description' => 'Create new users',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'edit_users', 
                'slug' => 'edit-users', 
                'group' => 'users', 
                'description' => 'Edit existing users',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'delete_users', 
                'slug' => 'delete-users', 
                'group' => 'users', 
                'description' => 'Delete users',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'impersonate_users', 
                'slug' => 'impersonate-users', 
                'group' => 'users', 
                'description' => 'Impersonate other users',
                'is_protected' => true,
                'is_assignable' => false
            ],
            
            // Role management
            [
                'name' => 'view_roles', 
                'slug' => 'view-roles', 
                'group' => 'roles', 
                'description' => 'View role list and details',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'create_roles', 
                'slug' => 'create-roles', 
                'group' => 'roles', 
                'description' => 'Create new roles',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'edit_roles', 
                'slug' => 'edit-roles', 
                'group' => 'roles', 
                'description' => 'Edit existing roles',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'delete_roles', 
                'slug' => 'delete-roles', 
                'group' => 'roles', 
                'description' => 'Delete roles',
                'is_protected' => true,
                'is_assignable' => true
            ],
            
            // Permission management
            [
                'name' => 'view_permissions', 
                'slug' => 'view-permissions', 
                'group' => 'permissions', 
                'description' => 'View permission list',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'edit_permissions', 
                'slug' => 'edit-permissions', 
                'group' => 'permissions', 
                'description' => 'Edit role permissions',
                'is_protected' => true,
                'is_assignable' => true
            ],
            
            // Content management
            [
                'name' => 'view_content', 
                'slug' => 'view-content', 
                'group' => 'content', 
                'description' => 'View all content',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'create_content', 
                'slug' => 'create-content', 
                'group' => 'content', 
                'description' => 'Create new content',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'edit_content', 
                'slug' => 'edit-content', 
                'group' => 'content', 
                'description' => 'Edit existing content',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'delete_content', 
                'slug' => 'delete-content', 
                'group' => 'content', 
                'description' => 'Delete content',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'publish_content', 
                'slug' => 'publish-content', 
                'group' => 'content', 
                'description' => 'Publish/unpublish content',
                'is_protected' => true,
                'is_assignable' => true
            ],
            
            // Settings
            [
                'name' => 'view_settings', 
                'slug' => 'view-settings', 
                'group' => 'settings', 
                'description' => 'View application settings',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'edit_settings', 
                'slug' => 'edit-settings', 
                'group' => 'settings', 
                'description' => 'Edit application settings',
                'is_protected' => true,
                'is_assignable' => true
            ],
            
            // Media management
            [
                'name' => 'upload_media', 
                'slug' => 'upload-media', 
                'group' => 'media', 
                'description' => 'Upload media files',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'manage_media', 
                'slug' => 'manage-media', 
                'group' => 'media', 
                'description' => 'Manage all media files',
                'is_protected' => true,
                'is_assignable' => true
            ],
            
            // System
            [
                'name' => 'access_admin', 
                'slug' => 'access-admin', 
                'group' => 'system', 
                'description' => 'Access admin dashboard',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'view_activity_logs', 
                'slug' => 'view-activity-logs', 
                'group' => 'system', 
                'description' => 'View system activity logs',
                'is_protected' => true,
                'is_assignable' => true
            ],
            [
                'name' => 'clear_cache', 
                'slug' => 'clear-cache', 
                'group' => 'system', 
                'description' => 'Clear application cache',
                'is_protected' => true,
                'is_assignable' => true
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create roles and assign permissions
        $roles = [
            [
                'name' => 'Super Administrator',
                'slug' => User::ROLE_SUPER_ADMIN,
                'description' => 'Has full access to all features and settings',
                'is_protected' => true,
                'level' => 100,
                'permissions' => Permission::pluck('slug')->toArray()
            ],
            [
                'name' => 'Administrator',
                'slug' => User::ROLE_ADMIN,
                'description' => 'Has full access to most features',
                'is_protected' => true,
                'level' => 90,
                'permissions' => [
                    'access-admin',
                    'view-users', 'create-users', 'edit-users', 'delete-users',
                    'view-roles', 'view-permissions',
                    'view-content', 'create-content', 'edit-content', 'delete-content', 'publish-content',
                    'view-settings', 'edit-settings',
                    'upload-media', 'manage-media',
                    'view-activity-logs', 'clear-cache',
                ]
            ],
            [
                'name' => 'Editor',
                'slug' => User::ROLE_EDITOR,
                'description' => 'Can create and edit content but cannot publish',
                'is_protected' => true,
                'level' => 50,
                'permissions' => [
                    'access-admin',
                    'view-content', 'create-content', 'edit-content',
                    'upload-media',
                ]
            ],
            [
                'name' => 'Company',
                'slug' => User::ROLE_COMPANY,
                'description' => 'Company account with limited access',
                'is_protected' => true,
                'level' => 30,
                'permissions' => [
                    'access-admin',
                    'view-content', 'create-content', 'edit-content',
                    'upload-media',
                ]
            ],
            [
                'name' => 'Candidate',
                'slug' => User::ROLE_CANDIDATE,
                'description' => 'Regular user with basic access',
                'is_protected' => true,
                'level' => 20,
                'permissions' => [
                    'access-admin',
                    'view-content',
                ]
            ],
            [
                'name' => 'User',
                'slug' => User::ROLE_USER,
                'description' => 'Basic authenticated user',
                'is_protected' => true,
                'level' => 10,
                'permissions' => [
                    'access-admin',
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
            
            // Get the permission IDs
            $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id')->toArray();
            $role->permissions()->sync($permissionIds);
        }

        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // Assign super admin role to admin user
        $admin->roles()->syncWithoutDetaching([
            Role::where('slug', User::ROLE_SUPER_ADMIN)->first()->id
        ]);

        // Create a test user for each role
        $this->createTestUser('Editor', 'editor@example.com', User::ROLE_EDITOR);
        $this->createTestUser('Company', 'company@example.com', User::ROLE_COMPANY);
        $this->createTestUser('Candidate', 'candidate@example.com', User::ROLE_CANDIDATE);
        $this->createTestUser('Regular User', 'user@example.com', User::ROLE_USER);
    }

    /**
     * Create a test user with the given role
     *
     * @param string $name
     * @param string $email
     * @param string $roleSlug
     * @return void
     */
    private function createTestUser(string $name, string $email, string $roleSlug): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // Assign role to user
        $role = Role::where('slug', $roleSlug)->first();
        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}
