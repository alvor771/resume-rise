<?php

namespace App\Data;

class MenuData
{
    /**
     * Get sample menu data for testing
     * 
     * @return array
     */
    public static function getSampleMenu(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Home',
                'path' => '/',
                'icon' => 'fas fa-home',
                'layout' => 'layout.app',
                'parent_id' => null,
                'order' => 1,
                'is_visible' => true,
                'is_guest' => true,
                'is_auth' => false,
                'permissions' => [],
                'children' => []
            ],
            [
                'id' => 1,
                'title' => 'nav.resumeBuilder',
                'path' => '/cv-generator',
                'icon' => 'fas fa-home',
                'layout' => 'layout.app',
                'parent_id' => null,
                'order' => 1,
                'is_visible' => true,
                'is_guest' => true,
                'is_auth' => false,
                'permissions' => [],
                'children' => []
            ],
            [
                'id' => 2,
                'title' => 'About',
                'path' => '/about',
                'icon' => 'fas fa-info-circle',
                'layout' => 'layout.app',
                'parent_id' => null,
                'order' => 2,
                'is_visible' => true,
                'is_guest' => true,
                'is_auth' => false,
                'permissions' => [],
                'children' => [
                    [
                        'id' => 4,
                        'title' => 'Our Team',
                        'path' => '/about/team',
                        'icon' => 'fas fa-users',
                        'layout' => 'layout.app',
                        'parent_id' => 2,
                        'order' => 1,
                        'is_visible' => true,
                        'is_guest' => true,
                        'is_auth' => false,
                        'permissions' => [],
                        'children' => [
                            [
                                'id' => 8,
                                'title' => 'Management',
                                'path' => '/about/team/management',
                                'icon' => 'fas fa-user-tie',
                                'layout' => 'layout.app',
                                'parent_id' => 4,
                                'order' => 1,
                                'is_visible' => true,
                                'is_guest' => true,
                                'is_auth' => false,
                                'permissions' => []
                            ]
                        ]
                    ],
                    [
                        'id' => 5,
                        'title' => 'Company History',
                        'path' => '/about/history',
                        'icon' => 'fas fa-history',
                        'layout' => 'layout.app',
                        'parent_id' => 2,
                        'order' => 2,
                        'is_visible' => true,
                        'is_guest' => true,
                        'is_auth' => false,
                        'permissions' => []
                    ],
                    [
                        'id' => 9,
                        'title' => 'Careers',
                        'path' => '/about/careers',
                        'icon' => 'fas fa-briefcase',
                        'layout' => 'layout.app',
                        'parent_id' => 2,
                        'order' => 3,
                        'is_visible' => true,
                        'is_guest' => true,
                        'is_auth' => false,
                        'permissions' => []
                    ]
                ]
            ],
            [
                'id' => 3,
                'title' => 'Help',
                'path' => '/help-center',
                'icon' => 'fas fa-question',
                'layout' => 'layout.app',
                'parent_id' => null,
                'order' => 3,
                'is_visible' => true,
                'is_guest' => true,
                'is_auth' => false,
                'permissions' => [],
                'children' => [
                    [
                        'id' => 6,
                        'title' => 'HELP',
                        'path' => '/help-center',
                        'icon' => 'fas fa-question-circle',
                        'layout' => 'layout.app',
                        'parent_id' => 3,
                        'order' => 1,
                        'is_visible' => true,
                        'is_guest' => true,
                        'is_auth' => false,
                        'permissions' => []
                    ],
                    [
                        'id' => 7,
                        'title' => 'Contact Support',
                        'path' => '/help-center/contact',
                        'icon' => 'fas fa-headset',
                        'layout' => 'layout.app',
                        'parent_id' => 3,
                        'order' => 2,
                        'is_visible' => true,
                        'is_guest' => true,
                        'is_auth' => false,
                        'permissions' => []
                    ]
                ]
            ],
            [
                'id' => 10,
                'title' => 'User Dashboard',
                'path' => '/dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'layout' => 'dashboard',
                'parent_id' => null,
                'order' => 4,
                'is_visible' => true,
                'is_guest' => false,
                'is_auth' => true,
                'permissions' => ['view_dashboard'],
                'children' => [
                    [
                        'id' => 11,
                        'title' => 'Profile',
                        'path' => '/dashboard/profile',
                        'icon' => 'fas fa-user',
                        'layout' => 'dashboard',
                        'parent_id' => 10,
                        'order' => 1,
                        'is_visible' => true,
                        'is_guest' => false,
                        'is_auth' => true,
                        'permissions' => ['view_profile']
                    ],
                    [
                        'id' => 12,
                        'title' => 'Settings',
                        'path' => '/dashboard/settings',
                        'icon' => 'fas fa-cog',
                        'layout' => 'dashboard',
                        'parent_id' => 10,
                        'order' => 2,
                        'is_visible' => true,
                        'is_guest' => false,
                        'is_auth' => true,
                        'permissions' => ['edit_settings']
                    ]
                ]
            ]
        ];
    }

    /**
     * Get flattened menu items (without children)
     * 
     * @return array
     */
    public static function getFlattenedMenu(): array
    {
        $menu = self::getSampleMenu();
        $flattened = [];
        
        $flatten = function ($items) use (&$flatten, &$flattened) {
            foreach ($items as $item) {
                $children = $item['children'] ?? [];
                unset($item['children']);
                $flattened[] = $item;
                
                if (!empty($children)) {
                    $flatten($children);
                }
            }
        };
        
        $flatten($menu);
        return $flattened;
    }
}
