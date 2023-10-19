<?php

namespace BasePack\Http\Livewire;

use Livewire\Component;
use BasePack\Models\NavLink;
use BasePack\Models\Permission;
use Illuminate\Support\Str;
use BasePack\Http\Traits\UserActionGuardTrait;

class MenuconfDashboard extends Component
{
    use UserActionGuardTrait;
    
    private $links;

    // protected $listeners = ['showMenuItemModal' => 'showMenuItemModal', 'updateLinkOrderIndices' => 'updateLinkOrderIndices'];

    public function mount()
    {
        $this->links = NavLink::where('level', 1)->get();
    }

    public function links(){
        $this->links = NavLink::where('level', 1)->orderBy('order_index', 'asc')->get();
        return $this->links;
    }

    public function render()
    {
        return view('basepack::livewire.menuconf-dashboard', [
            'links' => $this->links(),
        ]);
    }
}
