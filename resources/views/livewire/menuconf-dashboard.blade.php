<div>
    <div class="card card-primary">
        <div class="card-header">
            <div class="row">
                <h3 class="card-title">Menu Config <span><i class="fa fa-sliders" aria-hidden="true"></i></span></h3>
            </div>
        </div>
    
        <div class="card-body">
            <div >
                <ul class="list-group">
                    <div class="row">
                        <div class="col-2"><h6>Title   </h6></div>
                        <div class="col-2"><h6>Parent  </h6></div>
                        <div class="col-2"><h6>Enabled </h6></div>
                        <div class="col-2"><h6>Visible </h6></div>
                        <div class="col-2"><h6>Access  </h6></div>
                        <div class="col-2"><h6>Action  </h6></div>
                    </div>
                    @if ($links)
                        @foreach ($links as $link)
                            @if ($link->text)
                                <li class="list-group-item list-group-item-action">
                                    <div class="row">
                                        <div class="col-2">
                                            <h6>{{ $link->text }}</h6>
                                        </div>
                                        <div class="col-2"> {{ $this->getParentName($link->parent_id ?? '') }} </div>
                                        <div class="col-2"> {{ $link->enabled ? 'True' : 'False' }} </div>
                                        <div class="col-2"> {{ $link->visible ? 'True' : 'False' }} </div>
                                        <div class="col-2"> {{ $link->access}} </div>
                                        <div class="col-2">
                                             {{-- <button class="btn bg-primary-light white">Edit</button>  --}}
                                            @if ($this->roleCan('setting-appmenu.update'))
                                                <x-adminlte-button label="Edit" onclick="showMenuItemModal('{{ $link->id }}')"
                                                    class="bg-primary-light white"/>
                                                </div>
                                            @else
                                                <x-adminlte-button label="Edit"
                                                    class="bg-primary-light white disabled"/>
                                                </div>
                                            @endif

                                    </div>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>

            </div>
        </div>

    </div>



    @if ($this->roleCan('setting-appmenu.update'))
        <x-adminlte-modal wire:ignore.self id="modalCustom" title="Edit Menu Item" size="lg" theme="lightblue"
            icon="fas fa-user-lock" scrollable static-backdrop>
            <div style="height:500px;">
                <form>
                    <div class="row">
                        <div class="col-md-6 pr-5">
                            <div class="form-group">
                                <label for="linkName">Link Name</label>
                                <input  wire:model="currLink.text" type="text" name="linkName" class="form-control form-control-sm" id="linkName"
                                    placeholder="Link name">
                                    @error('currLink.text') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="roleName">Parent Id</label>
                                <input disabled  wire:model="currLink.parent_id" type="text" name="parentName" class="form-control form-control-sm" id="parentName"
                                    placeholder="Parent Link Id">
                                    @error('currLink.parent_id') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="roleName" style="display: block;">Access</label>
                                <select wire:model="currLink.access" class="form-control form-control-sm" aria-label="Menu item access">
                                    <option value="">no guard enabled</option>
                                    @if ($perms)
                                        @foreach ($perms as $perm)
                                            <option value="{{$perm->permission_name}}">{{$perm->permission_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('currLink.access') <span class="error text-danger">{{ $message }}</span> @enderror
                                
                            </div>
                            <div class="form-group">
                                <label for="roleEnabled">Enabled</label>
                                {{-- {{ dd($currLink) }} --}}
                                <input {{ $currLink['visible'] ? '' : 'disabled'}} wire:model="currLink.enabled" type="checkbox" name="roleEnabled" class="form-control form-control-sm" id="roleEnabled">
                                    @error('currLink.enabled') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="roleVisible">Visible</label>
                                <input   wire:model="currLink.visible" type="checkbox" name="roleVisible" class="form-control form-control-sm" id="roleVisible">
                                    @error('currLink.visible') <span class="error text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </form>
                <hr>


            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/>
                <x-adminlte-button class="ml-auto" theme="success" label="Save"  wire:click="saveMenuItem"/>
            </x-slot>
        </x-adminlte-modal> 
    @endif






    <script>

        var myModal;
        function showMenuItemModal(id){
            console.log('menu item id: ', id)
            window.livewire.emit('showMenuItemModal', id);
        }


        document.addEventListener('livewire:load', function () {
            myModal = new bootstrap.Modal(document.getElementById('modalCustom'));

            Livewire.on('showModal', e => {
                myModal.show();
            })

            Livewire.on('dismisModal', (e) => {
                if(e.closeModal){
                    myModal.hide();
                }
            })
        })
    </script>
</div>