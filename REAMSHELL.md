## Creating A Laravel App

### [Install Laravel](https://laravel.com/docs/10.x/installation#your-first-laravel-project)
```shell
$ composer create-project laravel/laravel example-app
```

### [Install Livewire](https://laravel-livewire.com/docs/2.x/quickstart#install-livewire)
```shell
$ composer require livewire/livewire
```

### [Install Laravel-AdminLte](https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Installation)
```shell
$ composer require jeroennoten/laravel-adminlte
$ php artisan adminlte:install
```
* To be able to customize the sidebar navigation and change adminlte styling, publish adminlte views.
    * ```shell
      $ php artisan adminlte:install --only=main_views
        ``` 

