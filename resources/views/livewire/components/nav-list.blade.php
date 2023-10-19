<div   wire:sortable-group="updateSubmenusIndices" wire:sortable.options="{ animation: 100 }">
    <ul class="list-group mt-2"  wire:sortable="updateMenuIndices"
        wire:sortable-group.item-group="parent-null"
       >
        @if ($links)
           
            @foreach ($links as $link)
                @if ($link->text)
                    <li class="list-group-item list-group-item-action" wire:sortable.item="{{ $link->id }}" wire:key="task-{{ $link->id }}">                        
                        <div class="row">
                            <div class="col-2">
                                <h6>{{ $link->text }}</h6>
                            </div>
                            <div class="col-2"> {{ $this->getParentName($link->parent_id ?? '') }} </div>
                            <div class="col-2"> {{ $link->enabled ? 'True' : 'False' }} </div>
                            <div class="col-2"> {{ $link->visible ? 'True' : 'False' }} </div>
                            <div class="col-2"> {{ $link->access_level}} </div>
                            <div class="col-2 row">
                                    {{-- <button class="btn bg-primary-light white">Edit</button>  --}}
                                @if ($this->roleCan('setting-appmenu.update'))
                                    <x-adminlte-button label="Edit" onclick="showMenuItemModal('{{ $link->id }}')"
                                        class="bg-primary-light white"/>
                                        <div style="display: flex; justify-content: center; align-items:center; cursor: pointer;" class="ml-3"  wire:sortable.handle>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                @else
                                    <x-adminlte-button label="Edit"
                                        class="bg-primary-light white disabled"/>
                                        <div style="display: flex; justify-content: center; align-items:center; cursor: pointer;" class="ml-3"  wire:sortable.handle>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                        </div>
                        @if ($this->linkHasChildren($link->id))
                            
                            {{-- @livewire('basepack::components.nav-list', ['links' => $this->getLinkChildren($link->id)], key(rand())) --}}
                            <ul class="list-group mt-2" id='nav-treeview-setting' wire:sortable-group.item-group="parent-{{$link->id}}">
                                @foreach ($this->getLinkChildren($link->id) as $link_child)
                                    <li wire:sortable-group.item="{{ $link_child->id }}"  
                                    class="list-group-item list-group-item-action panel cursor-pointer" id="{{$link->id}}-{{$link_child->id}}">
                                        <div class="row panel-row">
                                            <div class="col-2">
                                                <h6>{{ $link_child->text }}</h6>
                                            </div>
                                            <div class="col-2"> {{ $this->getParentName($link_child->parent_id ?? '') }} </div>
                                            <div class="col-2"> {{ $link_child->enabled ? 'True' : 'False' }} </div>
                                            <div class="col-2"> {{ $link_child->visible ? 'True' : 'False' }} </div>
                                            <div class="col-2"> {{ $link_child->access_level}} </div>
                                            <div class="col-2 row">
                                                @if ($this->roleCan('setting-appmenu.update'))
                                                    <x-adminlte-button label="Edit" onclick="showMenuItemModal('{{ $link_child->id }}')"
                                                        class="bg-primary-light white"/>
                                                        <div style="display: flex; justify-content: center; align-items:center; cursor: pointer;" class="ml-3"  wire:sortable.handle>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @else
                                                    <x-adminlte-button label="Edit"
                                                        class="bg-primary-light white disabled"/>
                                                        <div style="display: flex; justify-content: center; align-items:center; cursor: pointer;" class="ml-3"  wire:sortable.handle>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @endif
                                                {{-- <div>
                                                </div> --}}
                                        </div>

                                        @if ($this->linkHasChildren($link_child->id))
                                            <ul class="mt-2 p-0" wire:sortable-group.item-group="parent-{{$link_child->id}}">
                                                @foreach ($this->getLinkChildren($link_child->id) as $link_child_new)
                                                    <li wire:sortable-group.item="{{ $link_child_new->id }}"  
                                                    class="list-group-item list-group-item-action panel cursor-pointer" id="{{$link_child->id}}-{{$link_child_new->id}}">
                                                        <div class="row panel-row">
                                                            <div class="col-2">
                                                                <h6>{{ $link_child_new->text }}</h6>
                                                            </div>
                                                            <div class="col-2"> {{ $this->getParentName($link_child_new->parent_id ?? '') }} </div>
                                                            <div class="col-2"> {{ $link_child_new->enabled ? 'True' : 'False' }} </div>
                                                            <div class="col-2"> {{ $link_child_new->visible ? 'True' : 'False' }} </div>
                                                            <div class="col-2"> {{ $link_child_new->access_level}} </div>
                                                            <div class="col-2 row">
                                                                @if ($this->roleCan('setting-appmenu.update'))
                                                                    <x-adminlte-button label="Edit" onclick="showMenuItemModal('{{ $link_child_new->id }}')"
                                                                        class="bg-primary-light white"/>
                                                                        <div style="display: flex; justify-content: center; align-items:center; cursor: pointer;" class="ml-3"  wire:sortable.handle>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <x-adminlte-button label="Edit"
                                                                        class="bg-primary-light white disabled"/>
                                                                        <div style="display: flex; justify-content: center; align-items:center; cursor: pointer;" class="ml-3"  wire:sortable.handle>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>


                                        @endif
                                    </li>
                                @endforeach
                            </ul>     
                        @endif
                    </li>
                @endif
            @endforeach
            
        @endif
    </ul>



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
                                <label for="accessLevel" style="display: block;">Acces Level</label>
                                <select wire:model="currLink.access_level" class="form-control form-control-sm" aria-label="Menu item access level">
                                    {{-- <option value="">no access level set</option> --}}
                                    @if ($perms)
                                        @foreach ($this->getUniqueAccessLevel() as $access_level)
                                            <option value="{{$access_level}}">{{$access_level}}</option>
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

        function showMenuItemModal(id){
            console.log('menu item id: ', id)
            window.livewire.emit('showMenuItemModal', id);
        }


        document.addEventListener('livewire:load', function () {
            var myModal;
            // let ulName = @this.listName;
            // console.log('list name: ', ulName)
            myModal = new bootstrap.Modal(document.getElementById('modalCustom'));
            

            // initSortable(ulName)
            Livewire.on('showModal', e => {
                myModal.show();
            })

            Livewire.on('dismisModal', (e) => {
                if(e.closeModal){
                    myModal.hide();
                }
            })

        })
        
        function initSortable(listName){
            var panelList = $(`#${listName}`);
            console.log(panelList)
            console.log('got here')
            panelList.sortable({
                handle: '.panel-row', 
                update: function() {
                    let data = []
                    $('.panel', panelList).each(function(index, elem) {
                        var $listItem = $(elem),
                            newIndex = $listItem.index(), id = $listItem.attr('id');     
                        data.push({id, newIndex}) 
                    });
                    window.livewire.emit('updateLinkOrderIndices', data)
                }
            }).disableSelection();

        }

        
        // initSortable('demo')
        
    </script>
</div>
