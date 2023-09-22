<?php

namespace BasePack\Http\Livewire;

use BasePack\Models\Role;
use BasePack\Models\Permission;
use BasePack\Models\RoleHasPermission;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use BasePack\Http\Traits\UserActionGuardTrait;

class AccessconfDashboard extends Component
{
    use UserActionGuardTrait;

    public $appRoles;
    public $appPerms;

    public $roleId;
    public $rolename = '';
    public $permName = '';

    private $unAssignedRolePerms;
    private $assignedRolePerms;

    protected $listeners = ['saveRole' => 'saveRole', 'editRole' => 'editRole'];
    protected $rules     = [
        'rolename' => 'required',
    ];

    protected $messages = [
        'rolename.required' => 'The role name cannot be empty.',
        'permName.required' => 'The permission name cannot be empty.',
        'unique' => 'This value already exists.',

    ];


    public function mount()
    {
        $this->appRoles  = Role::all();
        $this->appPerms  = Permission::all();
        $this->userPerms = $this->rolePermissions(); // determine all perm names for this user's role
    }

    public function syncRolesInDbWithFileBtn(){
       $synced = $this->syncRolesInDbWithFile(); 
       if($synced){
            flash()
                ->options(['timeout' => 3000])
                ->addSuccess('App roles in context.json now updated.');
       }else{
            flash()
                ->options(['timeout' => 3000])
                ->addError('App roles in context.json could not be updated');
       }
    }

    public function syncRolesInDbWithFile(){
        $appRoles = Role::all();
        $roles = [];
        $contexts = [];
        foreach ($appRoles as $role) {
            array_push($roles, (object)['Name' => $role->rolename]);
        }


        if(Storage::disk('public_uploads')->exists('/.well-known/context.json')){
            $prev = Storage::disk('public_uploads')->get('/.well-known/context.json');
            $prev = json_decode($prev);

            if(property_exists($prev, 'contexts')){
                $contexts = $prev->contexts;
            }

        }else{ // context.json does not exit

        }

        $fileData = json_encode(['roles' => $roles, 'contexts' => $contexts,]);
        $saved = Storage::disk('public_uploads')->put('/.well-known/context.json', $fileData);
        if($saved){
            return true;
        }        

        return false;
        
    }


    public function saveRole($roleId = null){
        // (roleId == null) = new role being added
        // else old role being updated
        $validated = $this->validate([
            'rolename' => 'required|unique:roles,rolename',
        ]);
        if($validated){
            Role::create([
                'rolename'    => $this->rolename,
                'created_by'  => session('user')->userName,
                'modified_by' => session('user')->userName
            ]);
            $this->appRoles = Role::all();
        }

        $this->rolename = '';
        $this->roleId   = '';
        $this->syncRolesInDbWithFile();
        $this->emit('dismisModalRole', ['closeModal' => true]);
    }

    public function deleteRole($roleId){
        $role = Role::find($roleId);
        $role->delete();

        RoleHasPermission::where('role_id', $roleId)->delete();   
        $this->appRoles = Role::all();
    }


    public function deletePermission($permId){
        $perm = Permission::find($permId);
        $perm->delete();

        RoleHasPermission::where('permission_id', $permId)->delete();
        $this->appPerms = Permission::all();
    }


    public function savePermission(){
        $validated = $this->validate([
            'permName' => ['required'],
            // 'permission_name' => ['unique:roles'],
        ]);
        if($validated){
            Permission::create([
                'permission_name' => $this->permName,
                'created_by'      => session('user')->userName,
                'modified_by'     => session('user')->userName,
            ]);
            $this->appPerms = Permission::all();
        }
        $this->permName = '';
        $this->emit('dismisModalPerms', ['closeModal' => true]);
    }

    public function editRole($roleId){
        $this->roleId = $roleId;
        $role = Role::find($this->roleId);
        $this->rolename = $role->rolename;
        
        $this->assignedRolePerms   = $this->getAssignedRolePerms();
        $this->unAssignedRolePerms = $this->getUnAssignedRolePerms();

        $this->emit('showRoleModal');
    }

    public function addPermToRole($permId){
        RoleHasPermission::firstOrcreate(
            [ 
                'role_id'       => $this->roleId,
                'permission_id' => $permId,
            ],
            [
            'role_id'       => $this->roleId,
            'permission_id' => $permId,
            'created_by'    => session('user')->userName,
            'modified_by'   => session('user')->userName,
        ]);
        $this->assignedRolePerms   = $this->getAssignedRolePerms();;
        $this->unAssignedRolePerms = $this->getUnAssignedRolePerms();
    }

    public function removePermFromRole($permId){
        if($this->roleId){
            RoleHasPermission::where('role_id', $this->roleId)->where('permission_id', $permId)->delete();
            $this->assignedRolePerms   = $this->getAssignedRolePerms();
            $this->unAssignedRolePerms = $this->getUnAssignedRolePerms();
        }
    }

    public function getUnAssignedRolePerms(){
        if($this->roleId){
            $assignedRolePerms  = $this->getAssignedRolePerms(true);
            $unAssignedRolePerms = Permission::whereNotIn('id', $assignedRolePerms)->get(); 
            return $unAssignedRolePerms;   
        }
        return [];
    }

    public function getAssignedRolePerms($ids=false){
        if($this->roleId){
            $assignedRolePermsIds = RoleHasPermission::where('role_id', $this->roleId)->pluck('permission_id');
            $assignedRolePermsIds = $assignedRolePermsIds->toArray();
            if($ids){
                $assignedRolePerms = Permission::whereIn('id', $assignedRolePermsIds)->pluck('id');
                return $assignedRolePerms;
            }else{
                $assignedRolePerms = Permission::whereIn('id', $assignedRolePermsIds)->get();
                return $assignedRolePerms;
            }

        }        
    }

    public function render()
    {
        return view('basepack::livewire.accessconf-dashboard', [
            'unAssignedRolePerms' => $this->unAssignedRolePerms,
            'assignedRolePerms'   => $this->assignedRolePerms,
        ]);
    }
}
