<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'dashboard.index']);
        Permission::create(['name' => 'user.index']);
        Permission::create(['name' => 'user.create']);
        Permission::create(['name' => 'user.edit']);
        Permission::create(['name' => 'user.delete']);
        Permission::create(['name' => 'role.index']);
        Permission::create(['name' => 'role.create']);
        Permission::create(['name' => 'role.edit']);
        Permission::create(['name' => 'role.delete']);
        Permission::create(['name' => 'role.assign']);
        Permission::create(['name' => 'permission.index']);
        Permission::create(['name' => 'permission.create']);
        Permission::create(['name' => 'permission.edit']);
        Permission::create(['name' => 'permission.delete']);

        $superadminRole = Role::create(['name' => 'superadmin']);
        // gets all permissions via Gate::before rule

        // create roles and assign existing permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('dashboard.index');
        $adminRole->givePermissionTo('user.index');
        $adminRole->givePermissionTo('user.create');
        $adminRole->givePermissionTo('user.edit');
        $adminRole->givePermissionTo('user.delete');

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo('dashboard.index');

        // create users
        $user = User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@admin.com',
            'password' => bcrypt('Superadmin321+')
        ]);
        $user->assignRole($superadminRole);

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin321+')
        ]);
        $user->assignRole($adminRole);

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@admin.com',
            'password' => bcrypt('user321+')
        ]);
        $user->assignRole($userRole);

        // reset cached roles and permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

    }
}
