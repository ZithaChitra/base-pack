<?php

namespace BasePack\Http\Livewire\Components;

use Livewire\Component;
use BasePack\Models\NavLink;
use BasePack\Models\Permission;
use BasePack\Http\Traits\UserActionGuardTrait;
use BasePack\Models\Role;
use Illuminate\Support\Str;

class NavList extends Component
{
    use UserActionGuardTrait;

    public $links;
    private $perms;
    public $listName;
    public $reRender = false;
    // public $userPerms;

    public $currLinkId;
    public $currLink = [
        'text'      => '',
        'parent_id' => '',
        'enabled'   => true,
        'visible'   => true,
        // 'access_level' => 3
    ];

    protected $listeners = ['showMenuItemModal' => 'showMenuItemModal', 'updateLinkOrderIndices' => 'updateLinkOrderIndices'];

    public function mount()
    {
        // $this->links = NavLink::all();
        $this->perms = Permission::all();
        $this->userPerms = $this->rolePermissions(); // determine all perm names for this user's role
        $this->listName = Str::random();        
    }
    
    public function booted(){    
        $this->dispatchBrowserEvent('listNameSet', ['data' => $this->listName]);
    }


    public function perms(){
        $this->perms = Permission::all();
        return $this->perms;
    }

    public function showMenuItemModal($menuItemId){

        $currLink = NavLink::find($menuItemId);
        if($currLink){
            $this->currLink = $currLink->toArray();
            $this->currLink['parent_id'] = $this->currLink['parent_id'] ?? '';
            $this->currLink['enabled']   = (boolean)$this->currLink['enabled'];
            $this->currLink['visible']   = (boolean)$this->currLink['visible'];
        
        }
        $this->emit('showModal');
    }


    public function getParentName($parentId){
        $parent = NavLink::find($parentId);
        if($parent){
            return $parent->text;
        }
        return '';
    }

    public function linkHasChildren($linkId){
        $count = NavLink::where('parent_id', $linkId)->count();
        return $count > 0 ? true : false;
    }

    public function getLinkChildren($linkId){
        $children = NavLink::where('parent_id', $linkId)
                        ->orderBy('order_index', 'asc')
                        ->get();
        return $children;
    }


    public function getUniqueAccessLevel(){
        return Role::distinct()->pluck('access_level');
    }


    public function saveMenuItem(){
        $currLink = NavLink::find($this->currLink['id']);
        if($currLink){
            $currLink->text    = $this->currLink['text'];
            $currLink->access  = $this->currLink['access'];
            $currLink->enabled = $this->currLink['enabled'];
            $currLink->visible = $this->currLink['visible'];
            $currLink->access_level = $this->currLink['access_level'];
            $currLink->save();
        }
        $this->emit('dismisModal', ['closeModal' => true]);
    }

    public function updateSubmenusIndices($data){
        // dd('updateSubmenuIndices', $data);
        foreach ($data as $key => $parent) {
            $parentId = explode('-', $parent['value'])[1];
            $parentId = $parentId == 'null' ? null : $parentId;
            $parentD  = NavLink::find($parentId);
            $pLevel   = $parentD ? $parentD->level : 0; #pLevel = 0 means we moved to top level: level 1

            $children = $parent['items'];
            foreach ($children as $link) {
                $linkD = NavLink::find($link['value']);
                $linkD->parent_id = $parentId;
                if($pLevel == 0){
                    // continue;

                    $highestOrderIndex  = (int)NavLink::where('level', 1)->max('order_index');
                    $linkD->order_index = $highestOrderIndex + 1;
                    $linkD->level = 1;
                }else{
                    $linkD->order_index = $link['order'] - 1;
                    $linkD->level       = $pLevel + 1;

                }
                $linkD->save();
            }
        }
        $this->links = $this->links();
        return;
    }



    public function updateMenuIndices($data){
        // dd('update menu Indices', $data);
        foreach ($data as $key => $link) {
            $linkD = NavLink::find($link['value']);
            if($linkD){
                $linkD->order_index = $link['order'] - 1;
                $linkD->save();
            }
        }
        $this->links = $this->links();
        return;
    }

    public function links(){
        $this->links = NavLink::where('level', 1)->orderBy('order_index', 'asc')->get();
        return $this->links;
    }



    public function render()
    {
        return view('basepack::livewire.components.nav-list', [
            'currLink' => $this->currLink,
            'perms'    => $this->perms()
        ]);
    }
}
