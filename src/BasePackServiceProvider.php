<?php

namespace BasePack;

// require_once __DIR__.'/../vendor/autoload.php';
use Livewire\Livewire;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

use BasePack\Http\Middleware\IdservAuth;
use BasePack\Http\Livewire\CustomFooter;
use BasePack\Http\Livewire\AdminDashboard;
use BasePack\Http\Livewire\AppconfDashboard;
use BasePack\Http\Livewire\Auth\Authenticate;
use BasePack\Http\Livewire\MenuconfDashboard;
use BasePack\Http\Livewire\AccessconfDashboard;
use BasePack\Console\Commands\UpdateAppContexts;

class BasePackServiceProvider extends ServiceProvider 
{
    public function register()
    {
    }

    public function boot(Kernel $kernel)
    {

        if(class_exists(Livewire::class)){
            Livewire::component('basepack::custom-footer', CustomFooter::class);
            Livewire::component('basepack::admin-dashboard', AdminDashboard::class);
            Livewire::component('basepack::auth.authenticate', Authenticate::class);
            Livewire::component('basepack::appconf-dashboard', AppconfDashboard::class);
            Livewire::component('basepack::menuconf-dashboard', MenuconfDashboard::class);
            Livewire::component('basepack::accessconf-dashboard', AccessconfDashboard::class);

        }
        if ($this->app->runningInConsole()) {
            $this->commands([UpdateAppContexts::class]);
            $this->publishes([
            __DIR__.'/../config/adminlte.php' => config_path('adminlte.php'),

                ], 'config');
            
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/basepack'),
                ], 'views');

            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js'),
                __DIR__.'/../resources/views/vendor' => resource_path('views/vendor'),
                __DIR__.'/../resources/views/errors' => resource_path('views/errors'),
                __DIR__.'/../resources/public/themes' => public_path('themes'),
                __DIR__.'/../resources/public/vendor' => public_path('vendor'),
                ], 'assets');
        }


        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
            ], 'seeders');

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('idservAuth', IdservAuth::class);
        $this->loadMigrationsFrom(__DIR__. '/../database/migrations');
        
        
        $this->loadRoutesFrom(__DIR__. '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'basepack');
        // Artisan::call('adminlte:install');  
    }

    public function isDeferred()
    {
        return false;
    }
}

?>
