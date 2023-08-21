## App Setup

### Register App with IDSERV (auth server) 
- Choose Preferred App Name
- Choose 'SPA' as App Type
- App code can be the same as App Name
- Redirect URL should be set to APP_URL/login

### Install Dependencies
- If prompted for answers choose '**no**'.
```shell
$ composer install
$ php artisan key:generate
$ npm install
```

### Configure ENV Variables
* Database connection values
    * set DB_CONNECTION=sqlite
    * create file named **database.sqlite** under database folder
* **IMPORTANT** VARS to check
    * APP_URL=app_url
    * AUTH_SERVER=idservur/
    * LIVEWIRE_ASSET_URL=
    * CONTEXT_FILE_ENDPOINT=api_url/.well-known/context.json
    * MIX_PKCE_CLIENT_ID=client_id
    * MIX_APP_URL=app_url
    * MIX_AUTH_SERVER=idserv_url/
    * MIX_GRAPHQL_ENDPOINT=api_url
    * CONTEXT_FILE_ENDPOINT=api_url/.well-known/context.json
    * DEFAULT_THEME=default

### Example Complete ENV Variable Config
```env
APP_DEBUG=true
APP_URL=app_url

DB_CONNECTION=sqlite
#DB_HOST=127.0.0.1
#DB_PORT=3306
#DB_DATABASE=laravel
#DB_USERNAME=root
#DB_PASSWORD=

DEFAULT_THEME=default

AUTH_SERVER=https://iddev.msbmicro.com/
LIVEWIRE_ASSET_URL=

CONTEXT_FILE_ENDPOINT=https://simapidev.msbmicro.com/.well-known/context.json
MIX_PKCE_CLIENT_ID=client_id
MIX_APP_URL=app_url
MIX_AUTH_SERVER=https://iddev.msbmicro.com/
MIX_GRAPHQL_ENDPOINT=https://simapidev.msbmicro.co/graphql
CONTEXT_FILE_ENDPOINT=https://simapidev.msbmicro.com/.well-known/context.json
```

### Build Assets
```shell
$ npm run dev
```

### Run Database Migration
- Create tables **contexts** and **roles**
    ```bash
    $ php artisan migrate
    ```

### Update App Contexts
- Updates Contexts.json file and local sqlite database
    ```shell
    php artisan contexts:update
    ```
### Customizations
Publish Assets
```shell
$ php artisan vendor:publish --provider="BasePack\BasePackServiceProvider" --tag="assets"
``` 
Publish Config Files
```shell
$ php artisan vendor:publish --provider="BasePack\BasePackServiceProvider" --tag="config"
``` 




### Update Livewire Assets URL
- If your app is not deployed on a root url e.g ***https:example.com/your_app***, then you should update the 
***LIVEWIRE_ASSET_URL=*** e.g
```shell
LIVEWIRE_ASSET_URL=your_app
```

### Run Dev Server
```shell
$ php artisan serve
```

## Misc

### Sidebar Nav
* You can make changes to the sidebar nav menu by updating the  **adminlte.php** config file.
    * nav links
    * logo, e.t.c...

 









