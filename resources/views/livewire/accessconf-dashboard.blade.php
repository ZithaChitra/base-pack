<div class="card card-primary">
    <div class="card-header">
        <div class="row">
            <h3 class="card-title">Access Config <span><i class="fa fa-sliders" aria-hidden="true"></i></span></h3>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6 pr-5">
                <div class="form-group">
                    <div class="mb-2" style="display: flex; justify-content:space-between; align-items: center;">
                        <label for="roles">App Roles</label> 
                        <div class="d-flex">
                            @if ($this->roleCan('setting-appaccess.update'))
                                <x-adminlte-button label="Refresh Roles" class="bg-primary-light white mr-2" wire:click="syncRolesInDbWithFileBtn"/>
                            @else
                                <x-adminlte-button label="Refresh Roles" class="bg-primary-light white mr-2 disabled"/>
                            @endif
                            @if ($this->roleCan('setting-appaccess.create'))
                                <x-adminlte-button label="Add Role" data-toggle="modal" data-target="#modalCustom3" class="bg-primary-light white"/>
                            @else
                                <x-adminlte-button label="Add Role"  class="bg-primary-light white disabled"/>
                            @endif
                        </div>
                    </div>
                    <ul class="list-group">
                        @if ($appRoles)
                            @foreach ($appRoles as $role)
                                <li class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p>{{ $role->rolename }}</p>
                                        </div>
                                        <div>
                                            @if ($this->roleCan('setting-appaccess.update'))
                                                <span onclick="showRoleModalWithId('{{ $role->id }}')" style="cursor: pointer; margin-right: 20px;"
                                                    class="disabled">
                                                    <i class="fa fa-plus"></i>
                                                </span>
                                            @else
                                                <span style="margin-right: 20px;"
                                                    class="disabled">
                                                    <i class="fa fa-plus"></i>
                                                </span>
                                            @endif

                                            @if ($this->roleCan('setting-appaccess.delete'))
                                                <span wire:click="deleteRole('{{ $role->id }}')" style="cursor: pointer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </span>
                                            @else
                                                <span>
                                                    <i class="fas fa-trash-alt"></i>
                                                </span>
                                            @endif

                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                {{-- <div>
                    @if (app('roleCan', ['setting-access.']))
                        <p>User can do staff</p>
                    @else
                        <p>User can not do staff</p>
                    @endif
                </div> --}}
            </div>
            <div class="col-md-6 pr-5">
                <div class="form-group">
                    <div class="mb-2" style="display: flex; justify-content:space-between; align-items: center;">
                        <label for="roles">App Permissions</label> 
                        @if ($this->roleCan('setting-appaccess.create'))
                            <x-adminlte-button label="Add Permission" data-toggle="modal" data-target="#modalCustom2" class="bg-primary-light white"/>
                        @else
                            <x-adminlte-button label="Add Permission" class="bg-primary-light white disabled"/>
                        @endif
                    </div>
                    <ul class="list-group">
                        @if ($appPerms)
                            @foreach ($appPerms as $permission)
                                <li class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p>{{ $permission->permission_name }}</p>
                                        </div>
                                        <div>
                                            {{-- <span wire:click="deletePermission({{ $permission->id }})" style="cursor: pointer">
                                                <i class="fas fa-trash-alt"></i>
                                            </span> --}}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div>

            @if ($this->roleCan('setting-appaccess.update'))
                {{-- edit role modal start --}}
                <x-adminlte-modal wire:ignore.self id="modalCustom" title="Update Role" size="lg" theme="lightblue"
                    icon="fas fa-user-lock" scrollable static-backdrop>
                    <div style="height:400px;">
                        <form>
                            <div class="row">
                                <div class="col-md-6 pr-5">
                                    <div class="form-group">
                                        <label for="roleName">Role Name</label>
                                        <input  wire:model="rolename" type="text" name="roleName" class="form-control form-control-sm" id="roleName"
                                            placeholder="Role name">
                                            @error('rolename') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>

                        <div class="row">
                            <div class="col-md-6 pr-5">
                                <div class="form-group">
                                    <label for="roleName">Permissions</label>
                                    <ul class="list-group">
                                        @if ($unAssignedRolePerms)
                                            @foreach ($unAssignedRolePerms as $permission)
                                                <li wire:click="addPermToRole('{{ $permission->id }}')" style="cursor: pointer;" class="list-group-item list-group-item-action">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <p>{{ $permission->permission_name }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 pr-5">
                                <div class="form-group">
                                    <label for="roleName">Assigned Permissions</label>
                                    <ul class="list-group">
                                        @if ($assignedRolePerms)
                                            @foreach ($assignedRolePerms as $permission)
                                                <li wire:click="removePermFromRole('{{ $permission->id }}')" style="cursor: pointer;" class="list-group-item list-group-item-action">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <p>{{ $permission->permission_name }}</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>
                    <x-slot name="footerSlot">
                        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
                        {{-- <x-adminlte-button class="ml-auto" theme="success" label="Save"  wire:click="saveRole"/> --}}
                    </x-slot>
                </x-adminlte-modal>  
                {{-- edit role modal end --}}
            @endif




            @if ($this->roleCan('setting-appaccess.create'))
                {{-- Permissions modal start --}}
                <x-adminlte-modal wire:ignore.self id="modalCustom2" title="Add New Permission" size="lg" theme="lightblue"
                    icon="fas fa-user-lock" scrollable static-backdrop>
                    <div style="height:100px;">
                        <form>
                            <div class="row">
                                <div class="col-md-6 pr-5">
                                    <div class="form-group">
                                        <label for="roleName">Permissison Name</label>
                                        <input  wire:model="permName" type="text" name="roleName" class="form-control form-control-sm" id="roleName"
                                            placeholder="Permission name">
                                            @error('permName') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <x-slot name="footerSlot">
                        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
                            <x-adminlte-button class="ml-auto" theme="success" label="Save"  wire:click="savePermission"/>
                        </x-slot>
                </x-adminlte-modal> 
                {{-- Permissions modal end --}}
            @endif


            @if ($this->roleCan('setting-appaccess.create'))
                {{-- add role modal start --}}
                <x-adminlte-modal wire:ignore.self id="modalCustom3" title="Add New Role" size="lg" theme="lightblue"
                    icon="fas fa-user-lock" scrollable static-backdrop>
                    <div style="height:100px;">
                        <form>
                            <div class="row">
                                <div class="col-md-6 pr-5">
                                    <div class="form-group">
                                        <label for="roleName">Role Name</label>
                                        <input  wire:model="rolename" type="text" name="roleName" class="form-control form-control-sm" id="roleName"
                                            placeholder="Role name">
                                            @error('rolename') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>

                    </div>
                    <x-slot name="footerSlot">
                        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
                            <x-adminlte-button class="ml-auto" theme="success" label="Save"  wire:click="saveRole"/>
                        </x-slot>
                </x-adminlte-modal>  
                {{-- add role modal end --}}
            @endif

                  
        </div>
    </div>



    <script>

        function closeModal(){
            console.log('closing modal')
            Livewire.emit('saveRole')
        }


        function showRoleModalWithId(roleId){
            console.log('role ID: ', roleId)
            window.livewire.emit('editRole', roleId);
        }

        document.addEventListener('livewire:load', function () {
            Livewire.on('dismisModalRole', (e) => {
                console.log('dismisModal event handler')
                let modal_ = document.getElementById('modalCustom3')
                if(e.closeModal){
                    $(modal_).modal('hide')
                }
            })
            
            Livewire.on('dismisModalPerms', (e) => {
                console.log('dismisModal event handler')
                let modal_ = document.getElementById('modalCustom2')
                if(e.closeModal){
                    $(modal_).modal('hide')
                }
            })

            Livewire.on('showRoleModal', (e) => {
                console.log('show modal event handler')
                var myModal = new bootstrap.Modal(document.getElementById('modalCustom'));
                myModal.show();
            })

        })
    </script>
</div>
