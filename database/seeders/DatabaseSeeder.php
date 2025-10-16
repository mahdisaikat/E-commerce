<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $user = User::firstOrCreate(
            ['email' => 'systemadmin@gmail.com'],
            [
                'name' => 'System Admin User',
                'password' => Hash::make('12345678'),
            ],
        );

        $role = Role::firstOrCreate(
            ['name' => 'systemadmin'],
            [
                'guard_name' => 'web',
                'display_name' => 'System Admin',
            ],
        );

        $user->assignRole([$role->id]);

        $array = [
            [
                'name' => 'superadmin',
                'guard_name' => 'web',
                'display_name' => 'Super Admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'admin',
                'guard_name' => 'web',
                'display_name' => 'Admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'teacher',
                'guard_name' => 'web',
                'display_name' => 'Teacher',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'student',
                'guard_name' => 'web',
                'display_name' => 'Student',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'register',
                'guard_name' => 'web',
                'display_name' => 'Register',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'user',
                'guard_name' => 'web',
                'display_name' => 'User',
                'created_at' => $now,
                'updated_at' => $now,
            ],

        ];
        Role::insertOrIgnore($array);

        $this->call([
            ConfigurationSeeder::class,
            PermissionsSeeder::class,
            SidebarSeeder::class,
            TagSeeder::class,
        ]);
    }
}
