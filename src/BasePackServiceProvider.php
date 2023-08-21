<?php

namespace BasePack;

// require_once __DIR__.'/../vendor/autoload.php';
use Illuminate\Support\ServiceProvider;
use Blessing\BrandsplashPackage\Http\Middleware\CapitalizeTitle;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Artisan;

class BasePackServiceProvider extends ServiceProvider 
{
    public function register()
    {
        // $this->app->bind('calculator', function($app){
        //     return new Calculator();
        // });
        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'blogpackage');
    }

    public function boot(Kernel $kernel)
    {
        // if ($this->app->runningInConsole()) {
        //     $this->publishes([
        //     __DIR__.'/../config/config.php' => config_path('blogpackage.php'),
        //         ], 'config');
            
        //     $this->publishes([
        //         __DIR__.'/../resources/views' => resource_path('views/vendor/blogpackage'),
        //         ], 'views');
        // }
        // // $kernel->pushMiddleware(CapitalizeTitle::class);
        // $this->loadMigrationsFrom(__DIR__. '/../database/migrations');
        $this->loadRoutesFrom(__DIR__. '/../routes/web.php');
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'blogpackage');
        // Artisan::call('adminlte:install');
        
    }
}

?>