<?php

namespace Shopex\LubanAdmin;

use File;
use Illuminate\Support\ServiceProvider;

class LubanAdminServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/../publish/views', 'admin');

        $this->publishes([
            __DIR__ . '/../publish/Middleware/' => app_path('Http/Middleware'),
            __DIR__ . '/../publish/migrations/' => database_path('migrations'),
            __DIR__ . '/../publish/Model/' => app_path(),
            __DIR__ . '/../publish/Controllers/' => app_path('Http/Controllers'),
            __DIR__ . '/../publish/resources/' => base_path('resources'),
            __DIR__ . '/../publish/views' => base_path('resources/views/vendor/admin'),
            __DIR__ . '/../publish/assets' => base_path('resources/assets/vendor/admin'),
            // __DIR__ . '/../publish/crudgenerator.php' => config_path('crudgenerator.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            'Shopex\LubanAdmin\LubanAdminCommand'
        );
    }
}
