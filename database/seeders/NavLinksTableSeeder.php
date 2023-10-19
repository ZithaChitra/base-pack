<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BasePack\Models\NavLink;

class NavLinksTableSeeder extends Seeder
{
    public $menuDefault = [
        [
            'text'         => 'Dashboard',
            'url'          => '/dashboard',
            'icon'         => 'fas fa-tachometer-alt',
            'active'       => ['dashboard'],
            'classes'      => '',
            'access'       =>  'dashboard.read',
            'visible'      => true,
            'enabled'      => true,
            'level'        => 1,
            'order_index'  => 0,
            'access_level' => 4,
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
            'level'       => 1,
            'order_index' => 1,
            'access_level' => 4,
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
            'level'       => 1,
            'order_index' => 2,
            'access_level' => 4,
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
            'level'       => 1,
            'order_index' => 3,
            'access_level' => 4,

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
            'level'       => 1,
            'order_index' => 4,
            'access_level' => 4,
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
                    'level'       => 2,
                    'order_index' => 0,
                    'access_level' => 9,

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
                    'level'       => 2,
                    'order_index' => 1,
                    'access_level' => 9,
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
                    'level'       => 2,
                    'order_index' => 2,
                    'access_level' => 9,
                    
                ],
                [
                    'text'        => 'Third Level Menu',
                    'url'         => '#',
                    'submenu'     => [
                        [
                            'text'        => 'Example Link 1',
                            'url'         => '/#',
                            'icon'        => 'fas fa-sliders-h',
                            'active'      => [],
                            'classes'     => '',
                            'access'      => 'setting-appmenu.read',
                            'visible'     => true,
                            'enabled'     => true,
                            'level'       => 3,
                            'order_index' => 0,
                            'access_level' => 9,
                            
                        ],
                        [
                            'text'        => 'Example Link 2',
                            'url'         => '/#',
                            'icon'        => 'fas fa-sliders-h',
                            'active'      => [],
                            'classes'     => '',
                            'access'      => 'setting-appmenu.read',
                            'visible'     => true,
                            'enabled'     => true,
                            'level'       => 3,
                            'order_index' => 1,
                            'access_level' => 9,
                            
                        ],
                    ],
                    'icon'        => 'fas fa-cog fa-fw',
                    'classes'     => '',
                    'access'      => '',
                    'visible'     => true,
                    'enabled'     => true,
                    'level'       => 2,
                    'order_index' => 3,
                    'access_level' => 9,
                    
                ],
            ],
            'icon'        => 'fas fa-cog fa-fw',
            'classes'     => '',
            'access'      => '',
            'visible'     => true,
            'enabled'     => true,
            'level'       => 1,
            'order_index' => 5,
            'access_level' => 9,
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
                        'level'       => $menuItem['level'],
                        'order_index' => $menuItem['order_index'],
                        'active'      => json_encode($menuItem['active'] ?? []),
                        'parent_id'   => $parentId,
                        'created_by'  => 'db_seeder',
                        'modified_by' => 'db_seeder',
                        'access_level' => $menuItem['access_level'] ?? 4,
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
