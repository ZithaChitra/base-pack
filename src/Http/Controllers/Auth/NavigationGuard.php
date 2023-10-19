<?php

namespace BasePack\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use BasePack\Models\Role;
use BasePack\Models\Permission;
use BasePack\Models\RoleHasPermission;
use BasePack\Models\NavLink;
use BasePack\Http\Traits\UserActionGuardTrait;

class NavigationGuard extends Controller
{
    use UserActionGuardTrait;

    public $menu = [
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
            'access'       =>  '',
            'visible'      => true,
            'access'       =>  '',
            'access_level' => 4,
            'visible'      => true,
            'enabled'      => true,
        ],
        [
            'text'         => 'Logout',
            'url'          => '/logout',
            'icon'         => 'fas fa-sign-out-alt',
            'topnav_right' => true,
            'access'       =>  '',
            'access_level' => 4,
            'visible'      => true,
            'enabled'      => true,
        ],
    ];

    public $appRoleName;
    public $rolePerms;

    public function __construct($appRoleName)
    {
        $links = NavLink::where('parent_id', null)->orderBy('order_index')->get()->toArray();
        foreach ($links as $link) {
            $link = $this->buildLinksGraph($link);
            $link['active'] = json_decode($link['active']);
            // $link['id'] = str_replace(' ', '', $link['text']); 
            array_push($this->menu, $link);
        }
        $this->appRoleName  = $appRoleName;   
        $this->rolePerms    = $this->getRolePerms();
    }

    public function buildLinksGraph($linkModel){
        $children_ = NavLink::where('parent_id', $linkModel['id'])->get()->toArray();
        $children = array_fill(0, count($children_), null);
    
        if($children_){
            foreach ($children_ as $child) {
                $child['active'] = json_decode($child['active']);
                // $child['id'] = str_replace(' ', '', $child['text']);
                $child['classes'] = 'panel-heading';
                $child = $this->buildLinksGraph($child);
                $children[$child['order_index']] = $child;
            }
            $linkModel['submenu'] = $children;
        }
        return $linkModel;
    }


    public function canGoToRoute($userRole, $route){
        $link  = $this->urlSelector($route);
        if($link){
            $accessLevel = $link->access_level;
            return $this->roleHasAccess($userRole, $accessLevel);
        }

        return false;
    }


    public function urlSelector(string $route){
        $urlParts   = explode('/', $route);
        $link       = null;
        if(count($urlParts)  == 1){ // single part url .eg /dashboard
            $link  = NavLink::where('url', '/' .$route)->first();
            return $link;
        }

        $urlParsed = $this->urlParser($route);

        foreach ($urlParsed as $url) {
            $url_ = NavLink::where('url', 'LIKE' ,'/' . $url . '%')->first();
            if($url_){
                $link = $url_;
                return $link;
            }
        }
        
        return $link;
    }

    public function urlParser($url){
        $urlParts = explode('/', $url);
        $size     = count($urlParts);
        $urlPartsParsed = [];
        for ($i = 1; $i < $size + 1; $i++) { 
            $singlePart =  implode('/', array_slice($urlParts, 0, $i));        
            $urlPartsParsed[] = $singlePart;
        }
        return $urlPartsParsed;
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
            // dd($menu);
            $isVisible = $menuItem['visible'];
            if(!$isVisible){
                $menu[$key]['classes'] = ' d-none ';
                continue;
            }
            

            if($menuItem['access_level']){
                $access = $menuItem['access_level'];
                // $hasAccess = key_exists($access, $this->rolePerms);
                $hasAccess = $this->roleHasAccess($this->appRoleName, (int)$menuItem['access_level']);
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
