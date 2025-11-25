<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

final class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $collection = collect([
            'admin' => collect([
                'manage users',
                'manage roles',
            ]),
        ]);

        $collection->each(function ($permission, $group): void {
            $permission->each(function ($name) use ($group): void {
                Permission::create(['group' => $group, 'name' => $name]);
            });
        });

        $admin = Role::create(['name' => 'admin', 'display_name' => 'Administrator', 'is_deletable' => false, 'is_editable' => false]);
        $admin->givePermissionTo(Permission::all());
    }
}
