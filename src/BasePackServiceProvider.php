<?php

namespace BasePack;

// require_once __DIR__.'/../vendor/autoload.php';
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use BasePack\Http\Livewire\Auth\Authenticate;
use BasePack\Http\Livewire\AdminDashboard;
use BasePack\Http\Livewire\CustomFooter;
use Illuminate\View\Compilers\BladeCompiler;
use BasePack\Http\Middleware\IdservAuth;
use BasePack\Console\Commands\UpdateAppContexts;
use Illuminate\Routing\Router;

class BasePackServiceProvider extends ServiceProvider 
{
    public function register()
    {
    }

    public function boot(Kernel $kernel)
    {
        if(class_exists(Livewire::class)){
            Livewire::component('basepack::auth.authenticate', Authenticate::class);
            Livewire::component('basepack::admin-dashboard', AdminDashboard::class);
            Livewire::component('basepack::custom-footer', CustomFooter::class);
        }
        $this->commands([UpdateAppContexts::class]);
        if ($this->app->runningInConsole()) {
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
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('idservAuth', IdservAuth::class);
        $this->loadMigrationsFrom(__DIR__. '/../database/migrations');
        
        
        $this->loadRoutesFrom(__DIR__. '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'basepack');
        // Artisan::call('adminlte:install');
        
    }
}

?>