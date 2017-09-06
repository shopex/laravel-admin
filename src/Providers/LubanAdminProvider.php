<?php

namespace Shopex\LubanAdmin\Providers;

use File;
use Illuminate\Support\ServiceProvider;
use Shopex\LubanAdmin\Console\Command;
use Shopex\LubanAdmin\Admin;

class LubanAdminProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->loadViewsFrom(__DIR__ . '/../../publish/views', 'admin');

        $this->publishes([
            __DIR__ . '/../../publish/Middleware/' => app_path('Http/Middleware'),
            __DIR__ . '/../../publish/migrations/' => database_path('migrations'),
            __DIR__ . '/../../publish/Model/' => app_path(),
            __DIR__ . '/../../publish/Controllers/' => app_path('Http/Controllers'),
        ], 'php');

        $this->publishes([
            __DIR__ . '/../../publish/resources/assets' => base_path('resources/assets/vendor/admin'),
            __DIR__ . '/../../publish/resources/crud-generator' => base_path('resources/crud-generator'),
            __DIR__ . '/../../publish/resources/views' => base_path('resources/views/vendor/admin'),
        ], 'resources');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Command::register();

        $this->app->singleton('LubanAdmin',function(){
            return new \Shopex\LubanAdmin\Admin;
        });
    }
}
