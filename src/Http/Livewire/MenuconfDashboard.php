<?php

namespace BasePack\Http\Livewire;

use Livewire\Component;
use BasePack\Models\NavLink;
use BasePack\Models\Permission;
use BasePack\Http\Traits\UserActionGuardTrait;

class MenuconfDashboard extends Component
{
    use UserActionGuardTrait;
    
    private $links;
    private $perms;

    public $currLinkId;
    public $currLink = [
        'text'      => '',
        'parent_id' => '',
        'enabled'   => true,
        'visible'   => true
    ];

    protected $listeners = ['showMenuItemModal' => 'showMenuItemModal'];

    public function mount()
    {
        $this->links = NavLink::all();
        $this->perms = Permission::all();
        $this->userPerms = $this->rolePermissions(); // determine all perm names for this user's role
    }

    public function links(){
        $this->links = NavLink::all();
        return $this->links;
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


    public function saveMenuItem(){
        $currLink = NavLink::find($this->currLink['id']);
        if($currLink){
            $currLink->text    = $this->currLink['text'];
            $currLink->access  = $this->currLink['access'];
            $currLink->enabled = $this->currLink['enabled'];
            $currLink->visible = $this->currLink['visible'];
            $currLink->save();
        }
        $this->emit('dismisModal', ['closeModal' => true]);
    }


    public function render()
    {
        return view('basepack::livewire.menuconf-dashboard', [
            'links'    => $this->links(),
            'currLink' => $this->currLink,
            'perms'    => $this->perms()
        ]);
    }
}
