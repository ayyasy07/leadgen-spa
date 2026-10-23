<?php

namespace App\Main;

class SimpleMenu
{
    public static function menu()
    {
        return [
            'dashboard' => [
                'icon' => 'home',
                'title' => 'Dashboard',
                'route_name' => 'admin.dashboard',
                'params' => [],
            ],
            'bookings' => [
                'icon' => 'book',
                'title' => 'Bookings',
                'route_name' => 'admin.bookings.index',
                'params' => [],
            ],
                'calendar' => [
                'icon' => 'calendar',
                'title' => 'Calendar',
                'route_name' => 'admin.calendar.index',
                'params' => [],
            ],
            'reports' => [
                'icon' => 'bar-chart-2',
                'title' => 'Reports',
                'route_name' => 'admin.reports.index',
                'params' => [],
            ],
            'contact-messages' => [
                'icon' => 'inbox',
                'title' => 'Contact Messages',
                'route_name' => 'admin.contact-messages.index',
                'params' => [],
            ],
            'newsletters' => [
                'icon' => 'mail',
                'title' => 'Newsletter',
                'route_name' => 'admin.newsletters.index',
                'params' => [],
            ],
            'devider',
            'services' => [
                'icon' => 'edit-3',
                'title' => 'Services & Packages',
                'sub_menu' => [
                    'treatments' => [
                        'icon' => 'layers',
                        'route_name' => 'admin.treatments.index',
                        'params' => [],
                        'title' => 'Treatments',
                    ],
                    'packages' => [
                        'icon' => 'box',
                        'route_name' => 'admin.packages.index',
                        'params' => [],
                        'title' => 'Packages',
                    ],
                ],
            ],
            'therapists' => [
                'icon' => 'users',
                'title' => 'Therapists',
                'route_name' => 'admin.therapists.index',
                'params' => [],
            ],
            'gallery' => [
                'icon' => 'image',
                'title' => 'Gallery',
                'sub_menu' => [
                    'galleries' => [
                        'icon' => 'image',
                        'route_name' => 'admin.galleries.index',
                        'params' => [],
                        'title' => 'Photo List',
                    ],
                    'gallery-categories' => [
                        'icon' => 'tag',
                        'route_name' => 'admin.gallery-categories.index',
                        'params' => [],
                        'title' => 'Categories',
                    ],
                ],
            ],
            'blogs' => [
                'icon' => 'file-text',
                'title' => 'Blogs',
                'sub_menu' => [
                    'blogs-list' => [
                        'icon' => 'file-text',
                        'route_name' => 'admin.blogs.index',
                        'params' => [],
                        'title' => 'Article List',
                    ],
                    'blog-categories' => [
                        'icon' => 'tag',
                        'route_name' => 'admin.blog-categories.index',
                        'params' => [],
                        'title' => 'Categories',
                    ],
                ],
            ],
            'testimonials' => [
                'icon' => 'message-square',
                'title' => 'Testimonials',
                'route_name' => 'admin.testimonials.index',
                'params' => [],
            ],
            'hero-sliders' => [
                'icon' => 'monitor',
                'title' => 'Hero Sliders',
                'route_name' => 'admin.hero-sliders.index',
                'params' => [],
            ],
            'users' => [
                'icon' => 'user-check',
                'title' => 'Users',
                'sub_menu' => [
                    'users-admins' => [
                        'icon' => 'shield',
                        'route_name' => 'admin.users.index',
                        'params' => ['role' => 'admin'],
                        'title' => 'Admins',
                    ],
                    'users-customers' => [
                        'icon' => 'user',
                        'route_name' => 'admin.users.index',
                        'params' => ['role' => 'customer'],
                        'title' => 'Customers',
                    ],
                ],
            ],
            'devider',
            'settings' => [
                'icon' => 'settings',
                'title' => 'Settings',
                'route_name' => 'admin.settings.index',
                'params' => [],
            ],
        ];
    }
}