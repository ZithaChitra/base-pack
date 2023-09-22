<?php

namespace BasePack\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use BasePack\Models\Role;
use BasePack\Models\Permission;
use BasePack\Models\RoleHasPermission;
use BasePack\Models\NavLink;

class NavigationGuard extends Controller
{

    public $menu = [
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
            'access'       =>  '',
            'visible'      => true,
            'access'       =>  '',
            'visible'      => true,
            'enabled'      => true,
        ],
        [
            'text'         => 'Logout',
            'url'          => '/logout',
            'icon'         => 'fas fa-sign-out-alt',
            'topnav_right' => true,
            'access'       =>  '',
            'visible'      => true,
            'enabled'      => true,
        ],
    ];

    public $appRoleName;
    public $rolePerms;

    public function __construct($appRoleName)
    {
        $links = NavLink::where('parent_id', null)->get()->toArray();
        foreach ($links as $link) {
            $link = $this->buildLinksGraph($link);
            $link['active'] = json_decode($link['active']);
            array_push($this->menu, $link);
        }
        $this->appRoleName  = $appRoleName;   
        $this->rolePerms    = $this->getRolePerms();
    }

    public function buildLinksGraph($linkModel){
        $children_ = NavLink::where('parent_id', $linkModel['id'])->get()->toArray();
        $children  = [];
        if($children_){
            foreach ($children_ as $child) {
                $child['active'] = json_decode($child['active']);
                array_push($children, $child);   
            }
            $linkModel['submenu'] = $children;
        }
        return $linkModel;
    }

    public static function canGoToRoute($userRole, $route){
        $link  = NavLink::where('url', '/' .$route)->first();
        if($link){
            $guard = $link->access;
            if($guard){
                $role  = Role::where('rolename', $userRole)->first();
                $perms = RoleHasPermission::where('role_id', $role->id)->pluck('permission_id');
                $perms = Permission::whereIn('id', $perms)->pluck('permission_name');
                foreach ($perms as $perm) {
                    if($perm == $guard){
                        return true;
                    }
                }
                return false; // user does not have access to this route
            }else{
                return true; // no guard on this route
            }

        }
    }

    
    public function getRolePerms(){
        $role = Role::where('rolename', $this->appRoleName)->first();   
        $rolePerms = [];
        if($role){
            $roleId = $role->id;
            $rolePermsIds = RoleHasPermission::where('role_id', $roleId)->pluck('permission_id');
            $rolePermsIds = $rolePermsIds->toArray();
            
            foreach ($rolePermsIds as $permId) {
                $perm = Permission::find($permId);
                if($perm){
                    $rolePerms[$perm->permission_name] = $perm->permission_name;                    
                }
            }
        }
        return $rolePerms;
    }


    public function filteredMenu($menu = [], $level = 1){
        $menu = $level === 1 ? $this->menu : $menu;

        foreach ($menu as $key =>$menuItem) {

            $isVisible = $menuItem['visible'];
            if(!$isVisible){
                $menu[$key]['classes'] = ' d-none ';
                continue;
            }
            

            if($menuItem['access']){
                $access = $menuItem['access'];
                $hasAccess = key_exists($access, $this->rolePerms);
                if(!$hasAccess){
                    $menu[$key]['classes'] = ' d-none ';
                    continue;
                }
            }

            // dd($menuItem);
            $isEnabled = $menuItem['enabled'];
            if(!$isEnabled){
                $menu[$key]['classes'] = ' disabled ';
            }

            $subMenu = $menuItem['submenu'] ?? [];
            if($subMenu){
                $filteredSubMenu = $this->filteredMenu($subMenu, $level + 1);
                $menu[$key]['submenu'] = $filteredSubMenu;
            }
        }
        
        return $menu;
    }
}
