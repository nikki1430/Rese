<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $masterPermission = Permission::create(['name' => 'master']);
        $editorPermission = Permission::create(['name' => 'editor']);

        Role::create(['name' => 'admin'])
            ->givePermissionTo($masterPermission);
        Role::create(['name' => 'writer'])
            ->givePermissionTo($editorPermission);
    }
}
