<div class="card card-primary">
    <div class="card-header">
        <div class="row">
            <h3 class="card-title">App Config <span><i class="fa fa-sliders" aria-hidden="true"></i></span></h3>
        </div>
    </div>

    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-md-6 pr-5">
                    <div class="form-group">
                        <label for="authServerUrl">Auth Server URL</label>
                        <input type="url" name="authServerUrl" class="form-control form-control-sm "  id="authServerUrl"
                            placeholder="Auth Server URL" wire:model="authServerUrl" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('authServerUrl') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="clientId">Client ID</label>
                        <input type="text" name="clientId" class="form-control form-control-sm" id="clientId"
                            placeholder="Client ID" wire:model="clientId" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('clientId') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="clientUrl">Client URL</label>
                        <input type="url" name="clientUrl" class="form-control form-control-sm" id="clientUrl"
                            placeholder="Client URL" wire:model="clientUrl" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('clientUrl') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6 pr-5">
                    <div class="form-group">
                        <label for="graphqlAPI">GraphQL API URL</label>
                        <input type="url" name="graphqlAPI" class="form-control form-control-sm" id="graphqlAPI"
                            placeholder="GraphQL API URL" wire:model="graphQLAPIURL" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('graphQLAPIURL') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="contextsUrl">Contexts URL</label>
                        <input type="url" name="contextsUrl" class="form-control form-control-sm" id="contextsUrl"
                            placeholder="Contexts URL" wire:model="contextsUrl" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('contextsUrl') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="theme">App Theme</label>
                        <select name="theme" wire:model="theme" class="form-control form-control-sm" id="theme" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @if ($themeOptions)
                                @foreach ($themeOptions as $themeOption)
                                    <option value="{{$themeOption->name}}">{{ $themeOption->name }}</option>
                                @endforeach
                            @endif
                            <option value="test">test empty theme</option>
                        </select>
                        {{-- <input type="url" name="theme" class="form-control form-control-sm" id="theme"
                            placeholder="Theme" wire:model="theme"> --}}
                        @error('theme') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <hr>

            {{-- DB --}}
            <div class="row">
                <div class="col-md-6 pr-5">
                    <div class="form-group">
                        <label for="dbDriver">DataBase Driver</label>
                        <select name="" name="dbDriver" wire:model="dbDriver" class="form-control form-control-sm" id="dbDriver" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            <option value="sqlite">SQLite</option>
                            <option value="pgsql">PostgreSQL</option>
                            <option value="mysql">MySQL</option>
                        </select>
                        @error('dbDriver') <span class="error text-danger">{{ $message }}</span> @enderror 
                        {{-- <input type="text" name="dbDriver" class="form-control form-control-sm" id="dbDriver"
                        placeholder="Database driver" wire:model="dbDriver">
                        @error('dbDriver') <span class="error text-danger">{{ $message }}</span> @enderror --}}
                    </div>

                    <div class="form-group">
                        <label for="dbHost">DataBase Host</label>
                        <input type="text" name="dbHost" class="form-control form-control-sm" id="dbHost"
                            placeholder="Database Host" wire:model="dbHost" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('dbHost') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="dbPort">DataBase Port</label>
                        <input type="text" name="dbPort" class="form-control form-control-sm" id="dbPort"
                            placeholder="Database Port" wire:model="dbPort" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('dbPort') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-md-6 pr-5">
                    <div class="form-group">
                        <label for="dbName">DataBase Name</label>
                        <input type="text" name="dbName" class="form-control form-control-sm" id="dbName"
                            placeholder="Database Name" wire:model="dbName" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('dbName') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="dbUserName">DataBase User Name</label>
                        <input type="text" name="dbUserName" class="form-control form-control-sm" id="dbUserName"
                            placeholder="Database User Name" wire:model="dbUserName" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('dbUserName') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="dbPassword">DataBase User Password</label>
                        <input type="password" name="dbPassword" class="form-control form-control-sm" id="dbPassword"
                            placeholder="Database User Password" wire:model="dbPassword" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('dbPassword') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>

            <hr>
            {{-- Git --}}
            <div class="row">
                <div class="col-md-6 pr-5">
                    <div class="form-group">
                        <label for="gitUser">Git User Name</label>
                        <input type="text" name="gitUser" class="form-control form-control-sm" id="gitUser"
                            placeholder="Git User Name" wire:model="gitUser" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('gitUser') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="gitPassword">Git User Password</label>
                        <input type="password" name="gitPassword" class="form-control form-control-sm" id="gitPassword"
                            placeholder="Git User Password" wire:model="gitPassword" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('gitPassword') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="gitRepoUrl">Git Repository URL</label>
                        <input type="text" name="gitRepoUrl" class="form-control form-control-sm" id="gitRepoUrl"
                            placeholder="Git Repository URL" wire:model="gitRepoUrl" {{ $this->roleCan('setting-appconf.update') ? '' : 'disabled' }}>
                            @error('gitRepoUrl') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-md-6 pr-5">
                    <div class="form-group">
                        <label for="gitRepoUrl" style="display: block">App Contexts</label>
                        @if ( $this->roleCan('setting-appconf.update'))
                            <button wire:click="refreshContexts"
                                type="button" class="btn bg-primary-light white "
                                >
                                <span >
                                    <svg style="width: 25px; height: 20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                Refresh app contexts</button>
                        @else
                            <button 
                                type="button" class="btn bg-primary-light white disabled"
                                >
                                <span >
                                    <svg style="width: 25px; height: 20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                Refresh app contexts</button>
                        @endif
                    </div>
                </div>
            </div>

        </form>
        @if ( $this->roleCan('setting-appconf.update'))
            <button wire:click="save" 
                type="button" class="btn bg-primary-light white">
                Save
            </button>

        @else
            <button 
                type="button" class="btn bg-primary-light white disabled">
                Save
            </button>
            
        @endif
    </div>
</div>
