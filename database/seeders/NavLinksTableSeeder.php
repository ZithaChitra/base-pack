<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BasePack\Models\NavLink;

class NavLinksTableSeeder extends Seeder
{
    public $menuDefault = [
        [
            'text'        => 'Dashboard',
            'url'         => '/dashboard',
            'icon'        => 'fas fa-tachometer-alt',
            'active'      => ['dashboard'],
            'classes'     => '',
            'access'      =>  'dashboard.read',
            'visible'     => true,
            'enabled'     => true,
        ],
        [
            'text'        => 'APNs',
            'url'         => '/apns',
            'icon'        => 'fa fa-broadcast-tower',
            'active'      => ['apns', 'apn*'],
            'classes'     => '',
            'access'      => 'apns.read',
            'visible'     => true,
            'enabled'     => true,
        ],
        [
            'text'        => 'Subscribers',
            'url'         => '/subscribers',
            'icon'        => 'fas fa-user',
            'active'      => ['subscribers', 'subscriber*'],
            'classes'     => '',
            'access'      => 'subscribers.read',
            'visible'     => true,
            'enabled'     => true,
        ],
        [
            'text'        => 'Sessions',
            'url'         => '/sessions',
            'icon'        => 'fas fa-signal',
            'active'      => ['sessions', 'session*'],
            'classes'     => '',
            'access'      => 'sessions.read',
            'visible'     => true,
            'enabled'     => true,
        ],
        [
            'text'        => 'Archived Data',
            'url'         => '/archived-data',
            'icon'        => 'fas fa-folder',
            'active'      => ['archived-data'],
            'classes'     => '',
            'access'      => 'archived-data.read',
            'visible'     => true,
            'enabled'     => true,
        ],

        [
            'text'        => 'App Admin',
            'submenu' => [
                [
                    'text'        => 'Access Config',
                    'url'         => '/xaccess',
                    'icon'        => 'fas fa-user-lock',
                    'active'      => ['xaccess'],
                    'classes'     => '',
                    'access'      => 'setting-appaccess.read',
                    'visible'     => true,
                    'enabled'     => true,
                ],
                [
                    'text'        => 'Menu Config',
                    'url'         => '/xmenu',
                    'icon'        => 'fas fa-link',
                    'active'      => ['xmenu'],
                    'classes'     => '',
                    'access'      => 'setting-appmenu.read',
                    'visible'     => true,
                    'enabled'     => true,
                ],
                [
                    'text'        => 'App Config',
                    'url'         => '/xadmin',
                    'icon'        => 'fas fa-sliders-h',
                    'active'      => ['xadmin'],
                    'classes'     => '',
                    'access'      => 'setting-appconf.read',
                    'visible'     => true,
                    'enabled'     => true,
                    
                ],
            ],
            'icon'        => 'fas fa-cog fa-fw',
            'classes'     => '',
            'access'      => '',
            'visible'     => true,
            'enabled'     => true,
        ],
    ];


    public function addMenuItemsToDB($menuItems, $parentId = null){
        try {
            foreach ($menuItems as $menuItem) {
                $createdMenuItem = NavLink::firstOrCreate(
                    [
                        'text'  => $menuItem['text'], 
                        'url'   => $menuItem['url'] ?? ''
                    ],
                    [
                        'text'        => $menuItem['text'], 
                        'url'         => $menuItem['url'] ?? '',
                        'icon'        => $menuItem['icon'],
                        'access'      => $menuItem['access'],
                        'visible'     => $menuItem['visible'],
                        'enabled'     => $menuItem['enabled'],
                        'classes'     => $menuItem['classes'],
                        'active'      => json_encode($menuItem['active'] ?? []),
                        'parent_id'   => $parentId,
                        'created_by'  => 'db_seeder',
                        'modified_by' => 'db_seeder',
                    ],
                );

                $subMenu = $menuItem['submenu'] ?? [];
                if($subMenu){
                    $this->addMenuItemsToDB($subMenu, $createdMenuItem->id);
                }

            }

        } catch (\Exception $e) {
            \Log::error('Could not add default theme to database: ' . $e->getMessage());
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addMenuItemsToDB($this->menuDefault);
    }
}
