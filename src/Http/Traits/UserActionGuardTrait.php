<?php

namespace BasePack\Http\Traits;
use Exception;
use BasePack\Models\Role;
use BasePack\Models\RoleHasPermission;
use BasePack\Models\Permission;

trait UserActionGuardTrait{
    public $userPerms = [];

    public function rolePermissions(){
        $userAppRole = session('user')->appRole ?? '';
        if($userAppRole){
            $role = Role::where('rolename', $userAppRole)->first();
            if($role){
                $rolePermsIds = RoleHasPermission::where('role_id', $role->id)->pluck('permission_id');
                $perms = Permission::whereIn('id', $rolePermsIds)->pluck('permission_name');
                return $perms;
            }
        }
        return [];
    }


    public function roleCan($expression){
        if($expression){
            foreach ($this->userPerms as $perm) {
                if($perm == $expression){
                    return true;
                }
            }
        }
        return false;
    }
}