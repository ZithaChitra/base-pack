<?php

namespace BasePack\Http\Traits;
use Exception;
use BasePack\Models\Role;
use BasePack\Models\RoleHasPermission;
use BasePack\Models\Permission;


class AccessRoles{

    public static function roleHasAccess($role, $minAccess){
        $role = Role::where('rolename', $role)->first();   
        if($role){
            $roleAcess  = $role->access_level;
            if($roleAcess >= $minAccess){
                return true;
            }
        } 
        return false;
    }
}

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

    public function roleHasAccess($role, $maxAccess){
        return AccessRoles::roleHasAccess($role, $maxAccess);
    }
}