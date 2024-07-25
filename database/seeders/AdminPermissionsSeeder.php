<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //define the permissions
        $permissions = [
            "roles" => [
                'see roles',
                'create roles',
                'edit roles'
            ],
            "admins" => [
                'see admins',
                'create admins',
                'edit admins'
            ],
            "categories" => [
                'see categories',
                'create categories',
                'edit categories'
            ]
        ];

        #loop over groups and create permissions accordingly
        foreach ($permissions as $key => $value) {
            foreach ($value as $permission) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'admin',
                    'permission_group' => $key
                ]);
            }
        }


        #create the super admin role and sync all permissions to it 
        $super_admin_role = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);

        $super_admin_role->syncPermissions(Permission::all());

        #assign the role to the super admin
        Admin::first()->syncRoles([$super_admin_role]);
    }
}
