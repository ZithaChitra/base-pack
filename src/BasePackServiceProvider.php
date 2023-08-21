<?php

namespace BasePack;

// require_once __DIR__.'/../vendor/autoload.php';
use Illuminate\Support\ServiceProvider;
use Blessing\BrandsplashPackage\Http\Middleware\CapitalizeTitle;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use BasePack\Http\Livewire\Auth\Authenticate;
use BasePack\Http\Livewire\AdminDashboard;
use BasePack\Http\Livewire\CustomFooter;
use Illuminate\View\Compilers\BladeCompiler;

class BasePackServiceProvider extends ServiceProvider 
{
    public function register()
    {
        $this->callAfterResolving(BladeCompiler::class, function(){
            if(class_exists(Livewire::class)){
                // Livewire::component('bauth.authenticate', Authenticate::class);
                // Livewire::component('admin-dashboard', AdminDashboard::class);
                // Livewire::component('custom-footer', CustomFooter::class);
            }
        });
        // $this->app->bind('calculator', function($app){
        //     return new Calculator();
        // });
        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'blogpackage');
    }

    public function boot(Kernel $kernel)
    {

        Livewire::component('auth.authenticate', Authenticate::class);
        Livewire::component('admin-dashboard', AdminDashboard::class);
        Livewire::component('custom-footer', CustomFooter::class);
        if ($this->app->runningInConsole()) {
            $this->publishes([
            __DIR__.'/../config/adminlte.php' => config_path('adminlte.php'),

                ], 'config');
            
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/basepack'),
                ], 'views');

            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js'),
                __DIR__.'/../resources/public/themes' => public_path('themes'),
                __DIR__.'/../resources/public/vendor' => public_path('vendor'),
                ], 'assets');
        }
        // // $kernel->pushMiddleware(CapitalizeTitle::class);
        // $this->loadMigrationsFrom(__DIR__. '/../database/migrations');
        // $this->force
        
        $this->loadRoutesFrom(__DIR__. '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'basepack');
        // Artisan::call('adminlte:install');
        
    }
}

?>