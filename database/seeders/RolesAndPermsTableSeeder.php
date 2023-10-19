<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BasePack\Models\Role;
use BasePack\Models\Permission;
use BasePack\Models\RoleHasPermission;

class RolesAndPermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $defaultRoles = ['Super Administrator', 'Administrator', 'User'];
            $accessMap = [
                'Super Administrator' => 9,
                'Administrator'       => 6,
                'User'                => 4,
            ];
            $defaultPerms = [
                'dashboard.read', 
                'apns.read', 
                'sessions.read', 
                'subscribers.read', 
                'archived-data.read',

                'setting-appaccess.read', 
                'setting-appaccess.update', 
                'setting-appaccess.delete', 
                'setting-appaccess.create', 

                'setting-appmenu.read', 
                'setting-appmenu.update',

                'setting-appconf.read',
                'setting-appconf.update',
            ];
            $defaultRolesPermsMap = [
                'Super Administrator' => $defaultPerms,
                'Administrator'       => $defaultPerms,
                'User'                => [
                    'dashboard.read', 
                    'apns.read', 
                    'sessions.read', 
                    'subscribers.read', 
                    'archived-data.read',
                    'setting-appaccess.read', 
                    'setting-appmenu.read', 
                    'setting-appconf.read',
                ],
            ];


            foreach ($defaultPerms as $perm) {
                Permission::firstOrCreate(
                    ['permission_name' => $perm],
                    [
                        'permission_name' => $perm,
                        'created_by'      => 'db_seeder',
                        'modified_by'     => 'db_seeder'
                    ]
                );
            }

            foreach ($defaultRoles as $role) {
                $newRole = Role::firstOrCreate(
                    ['rolename' => $role],
                    [
                        'rolename'     => $role,
                        'access_level' => $accessMap[$role],
                        'created_by'   => 'db_seeder',
                        'modified_by'  => 'db_seeder'
                    ]
                );
                if(array_key_exists($role, $defaultRolesPermsMap)){
                    $defaultPerms = $defaultRolesPermsMap[$role];
                    foreach ($defaultPerms as $permname) {
                        $perm = Permission::where('permission_name', $permname)->first();
                        if($perm){
                            RoleHasPermission::firstOrCreate(
                                ['role_id' => $newRole->id, 'permission_id' => $perm->id],
                                [
                                    'role_id'       => $newRole->id, 
                                    'permission_id' => $perm->id,
                                    'created_by'    => 'db_seeder',
                                    'modified_by'   => 'db_seeder'
                                ]
                            );
                        }
                    }
                }
            }


        } catch (\Exception $e) {
            \Log::error('Could not add default roles and perms to database: ' . $e->getMessage());
        }
    }
}
