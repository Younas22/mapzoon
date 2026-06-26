<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Modules managed by the system and the actions available on each.
     *
     * @var array<string, list<string>>
     */
    protected array $modules = [
        'users' => ['view', 'create', 'edit', 'delete'],
        'roles' => ['view', 'create', 'edit', 'delete'],
        'blogs' => ['view', 'create', 'edit', 'delete'],
        'projects' => ['view', 'create', 'edit', 'delete'],
        'tasks' => ['view', 'create', 'edit', 'delete'],
        'clients' => ['view', 'create', 'edit', 'delete'],
        'reviews' => ['view', 'create', 'edit', 'delete'],
        'teams' => ['view', 'create', 'edit', 'delete'],
        'leads' => ['view', 'create', 'edit', 'delete'],
        'credentials' => ['view', 'reveal', 'create', 'edit', 'delete'],
    ];

    /**
     * Roles to seed and the permission slugs each one starts with.
     * Super Admin always receives every permission regardless of this list.
     *
     * @var array<string, list<string>>
     */
    protected array $roles = [
        'Project Manager' => [
            'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
            'tasks.view', 'tasks.create', 'tasks.edit', 'tasks.delete',
            'clients.view', 'clients.edit',
            'teams.view', 'teams.edit',
            'reviews.view',
            'leads.view', 'leads.edit',
            'credentials.view', 'credentials.reveal', 'credentials.create', 'credentials.edit', 'credentials.delete',
        ],
        'SEO Specialist' => [
            'blogs.view', 'blogs.create', 'blogs.edit',
            'reviews.view', 'reviews.create', 'reviews.edit',
            'projects.view', 'tasks.view', 'tasks.edit',
        ],
        'Content Writer' => [
            'blogs.view', 'blogs.create', 'blogs.edit',
        ],
        'Support Agent' => [
            'clients.view', 'clients.create', 'clients.edit',
            'reviews.view', 'reviews.create',
            'leads.view', 'leads.create', 'leads.edit',
        ],
        'Client' => [
            'projects.view', 'tasks.view', 'reviews.view',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actionLabels = [
            'view' => 'View',
            'reveal' => 'Reveal',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ];

        foreach ($this->modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::query()->updateOrCreate(
                    ['slug' => "{$module}.{$action}"],
                    [
                        'name' => $actionLabels[$action].' '.Str::headline($module),
                        'module' => $module,
                    ]
                );
            }
        }

        $superAdmin = Role::query()->updateOrCreate(
            ['slug' => Role::SUPER_ADMIN],
            [
                'name' => 'Super Admin',
                'description' => 'Full, unrestricted access to every module and setting.',
                'is_system' => true,
            ]
        );
        $superAdmin->permissions()->sync(Permission::query()->pluck('id'));

        foreach ($this->roles as $name => $slugs) {
            $role = Role::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => null,
                    'is_system' => false,
                ]
            );

            $permissionIds = Permission::query()->whereIn('slug', $slugs)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }

        User::query()->updateOrCreate(
            ['email' => 'admin@mapzoon.com'],
            [
                'name' => 'Super Admin',
                'password' => 'Mapzoon@123',
                'role_id' => $superAdmin->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
