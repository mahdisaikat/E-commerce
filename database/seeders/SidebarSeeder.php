<?php

namespace Database\Seeders;

use App\Models\Sidebar;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SidebarSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            // Parent Sidebar Menus
            [
                'label' => 'Dashboard',
                'serial' => 1.00,
                'route' => 'dashboard',
                'icon' => 'fa-solid fa-house-chimney',
            ],
            [
                'label' => 'User Access Control',
                'serial' => 2.00,
                'route' => null,
                'icon' => 'fa-solid fa-users-gear',
            ],
            [
                'label' => 'School Management',
                'serial' => 3.00,
                'route' => null,
                'icon' => 'fa-solid fa-toolbox',
            ],
            [
                'label' => 'Config Settings',
                'serial' => 4.00,
                'route' => null,
                'icon' => 'fa-solid fa-sliders',
            ],

            // Child Sidebar Menus
            [
                'serial' => 2.10,
                'route' => 'users.index',
                'parent_id' => 2,
            ],
            [
                'serial' => 2.20,
                'route' => 'roles.index',
                'parent_id' => 2,
            ],
            [
                'serial' => 2.30,
                'route' => 'permissions.index',
                'parent_id' => 2,
            ],

            [
                'serial' => 3.10,
                'route' => 'designations.index',
                'parent_id' => 3,
            ],
            [
                'serial' => 3.20,
                'route' => 'teachers.index',
                'parent_id' => 3,
            ],
            [
                'serial' => 3.30,
                'route' => 'students.index',
                'parent_id' => 3,
            ],
            [
                'serial' => 3.40,
                'route' => 'sections.index',
                'parent_id' => 3,
            ],
            [
                'serial' => 3.50,
                'route' => 'subjects.index',
                'parent_id' => 3,
            ],
            [
                'serial' => 3.60,
                'route' => 'schedules.index',
                'parent_id' => 3,
            ],
            [
                'serial' => 3.70,
                'route' => 'exams.index',
                'parent_id' => 3,
            ],
            [
                'serial' => 3.80,
                'route' => 'results.index',
                'parent_id' => 3,
            ],

            [
                'serial' => 4.10,
                'route' => 'sidebars.index',
                'parent_id' => 4,
            ],
            [
                'serial' => 4.20,
                'route' => 'color.config',
                'parent_id' => 4,
            ],
            [
                'serial' => 4.30,
                'route' => 'system.settings',
                'parent_id' => 4,
            ],


        ];

        $permissions = Permission::all()->keyBy('name');

        foreach ($datas as &$data)
        {
            if (!empty($data['route']) && isset($permissions[$data['route']]))
            {
                $data['label'] = $permissions[$data['route']]->display_name;
                $data['permission_id'] = $permissions[$data['route']]->id;
            }

            Sidebar::updateOrCreate(
                [
                    'label' => $data['label'] ?? null,
                    'route' => $data['route'] ?? null
                ],
                [
                    'label' => $data['label'] ?? null,
                    'serial' => $data['serial'] ?? null,
                    'route' => $data['route'] ?? null,
                    'parent_id' => $data['parent_id'] ?? null,
                    'permission_id' => $data['permission_id'] ?? null,
                    'icon' => $data['icon'] ?? 'ri-record-circle-line',
                    'status' => 1,
                ]
            );
        }

    }
}
