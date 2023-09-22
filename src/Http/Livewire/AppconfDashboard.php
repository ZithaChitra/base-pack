<?php

namespace BasePack\Http\Livewire;

use Illuminate\Support\Facades\Artisan;
use BasePack\Models\SettingTheme;
use Livewire\Component;
use BasePack\Http\Traits\UserActionGuardTrait;

class AppconfDashboard extends Component
{

    use UserActionGuardTrait;

    public $clientId;
    public $clientUrl;
    public $contextsUrl;
    public $graphQLAPIURL;
    public $authServerUrl;
    
    public $dbDriver;
    public $dbHost;
    public $dbPort;
    public $dbName;
    public $dbUserName;
    public $dbPassword;
    
    public $gitUser;
    public $gitPassword;
    public $gitRepoUrl;
    
    public $theme;
    public $themeOptions = [
        
    ];

    protected $rules = [
        'theme'         => 'required',
        'graphQLAPIURL' => 'required|url',
        'authServerUrl' => 'required|url',
        'clientUrl'     => 'required|url',
        'contextsUrl'   => 'required|url',
        'clientId'      => 'required|integer',

        'dbDriver'      => 'required',
        'dbHost'        => '',
        'dbPort'        => '',
        'dbName'        => '',
        'dbUserName'    => '',
        'dbPassword'    => '',

        'gitUser'       => 'required',
        'gitPassword'   => 'required',
        'gitRepoUrl'    => 'required|url',
    ];

    protected $messages = [
        'theme.required'         => 'The theme cannot be empty.',

        'graphQLAPIURL.required' => 'The GraphQL API URL cannot be empty.',
        'graphQLAPIURL.url'      => 'The GraphQL API URL must be a valid URL',

        'authServerUrl.required' => 'The Auth Server URL cannot be empty.',
        'authServerUrl.url'      => 'The Auth Server URL must be a valid URL',

        'clientId.required'      => 'The Client Id cannot be empty.',
        'clientId.integer'       => 'The Client Id must be a number',

        'clientUrl.required'     => 'The Client URL cannot be empty.',
        'clientUrl.url'          => 'The Client URL must be a valid URL',

        'contextsUrl.required'   => 'The Contexts URL cannot be empty.',
        'contextsUrl.url'        => 'The Contexts URL must be a valid URL',

        'dbDriver.required'      => 'The Database Driver cannot be empty.',

        'gitUser.required'       => 'The Git User Name cannot be empty.',
        'gitPassword.required'   => 'The Git User Password cannot be empty.',
        'gitRepoUrl.required'    => 'The Git Repository Url cannot be empty.',
        'gitRepoUrl.url'         => 'The Git Repository Url must be a valid URL.',

    ];

    private $envMap = [
        'clientUrl'     => 'MIX_APP_URL',
        'theme'         => 'DEFAULT_THEME',
        'authServerUrl' => 'MIX_AUTH_SERVER',
        'clientId'      => 'MIX_PKCE_CLIENT_ID',
        'graphQLAPIURL' => 'MIX_GRAPHQL_ENDPOINT',
        'contextsUrl'   => 'CONTEXT_FILE_ENDPOINT',

        'dbDriver'      => 'DB_CONNECTION',
        'dbHost'        => 'DB_HOST',
        'dbPort'        => 'DB_PORT',
        'dbName'        => 'DB_DATABASE',
        'dbUserName'    => 'DB_USERNAME',
        'dbPassword'    => 'DB_PASSWORD',

        'gitUser'       => 'GIT_USER',
        'gitPassword'   => 'GIT_PWD',
        'gitRepoUrl'    => 'GIT_REPO_URL',
    ];

    public function mount()
    {
        $this->clientUrl     = env('MIX_APP_URL');
        $this->theme         = env('DEFAULT_THEME');
        $this->authServerUrl = env('MIX_AUTH_SERVER');
        $this->clientId      = env('MIX_PKCE_CLIENT_ID');
        $this->graphQLAPIURL = env('MIX_GRAPHQL_ENDPOINT');
        $this->contextsUrl   = env('CONTEXT_FILE_ENDPOINT');

        $this->dbDriver      = env('DB_CONNECTION');
        $this->dbHost        = env('DB_HOST');
        $this->dbPort        = env('DB_PORT');
        $this->dbName        = env('DB_DATABASE');
        $this->dbUserName    = env('DB_USERNAME');
        $this->dbPassword    = env('DB_PASSWORD');

        $this->gitUser       = env('GIT_USER');
        $this->gitPassword   = env('GIT_PWD');
        $this->gitRepoUrl    = env('GIT_REPO_URL');

        $this->themeOptions = SettingTheme::all();
        $this->userPerms = $this->rolePermissions(); // determine all perm names for this user's role
    }


    public function save()
    {
        $validated = $this->validate();
        $selectedDriver = $validated['dbDriver'];
        // dd($selectedDriver);
        $valKeys   = array_keys($validated);
        foreach ($valKeys as $key) {
            if (key_exists($key, $this->envMap)) {
                $name = $this->envMap[$key];
                $val  = $validated[$key];

                if (str_starts_with($key, 'db') && $key !== 'dbDriver') {
                    if ($selectedDriver == 'sqlite') {
                        $this->updateEnvironmentVariable($name, $val, true);
                        continue;
                    }
                }
                $this->updateEnvironmentVariable($name, $val);
            }
        }
    }
    

    // public function updatedTheme(){
    //     dd($this->theme);
    // }

    public function updateEnvironmentVariable($key, $value, $delPair = false)
    {
        $path = base_path('.env');

        if (is_bool(env($key))) {
            $old = env($key) ? 'true' : 'false';
        } elseif (env($key) === null) {
            $old = null;
        } else {
            $old = env($key);
        }

    

        if (file_exists($path)) {
            if ($delPair) {
                file_put_contents($path, str_replace(
                    "$key=" . $old,
                    "",
                    file_get_contents($path)
                ));
            } else {
                if ($old) {
                    file_put_contents($path, str_replace(
                        "$key=" . $old,
                        "$key=" . $value,
                        file_get_contents($path)
                    ));
                } else{
                    if (str_starts_with($key, 'DB') && !($key == 'DB_CONNECTION')) {
                        $envString = file_get_contents($path);
                        $pos = strpos($envString, 'DB_CONNECTION');
                        $pos = strpos($envString, PHP_EOL, $pos) + 1;
                        $envString = substr_replace($envString, "$key=$value" . PHP_EOL, $pos, 0);
                        file_put_contents($path, $envString);

                        return;
                    }
                }
            }
        }
    }

    public function refreshContexts()
    {
        // dd('hello-friend');
        $status = 0;
        $status = Artisan::call('contexts:update');
        if($status == 0){
            flash()
                ->options(['timeout' => 3000])
                ->addSuccess('App Contexts Updated');
        }else{   
            flash()
                ->options(['timeout' => 3000])
                ->addError('Could not update app contexts');
        }
    }

    public function render()
    {
        return view('basepack::livewire.appconf-dashboard');
    }
}
