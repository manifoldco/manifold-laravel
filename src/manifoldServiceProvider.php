<?php

namespace manifoldco\manifold;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache as SystemCache;
use manifoldco\manifold\API;
use manifoldco\manifold\Cache;
use manifoldco\manifold\Core;
use manifoldco\manifold\Commands\Refresh;
use manifoldco\manifold\Commands\Check;
use manifoldco\manifold\Commands\Env;

class manifoldServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/00-manifold.php' => config_path('00-manifold.php'),
            __DIR__.'/config/01-manifold.php' => config_path('01-manifold.php'),
            __DIR__.'/config/.manifold.cache.key' => storage_path('.manifold.cache.key'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Refresh::class,
                Check::class,
                Env::class,
            ]);
        }

        // $core = new Core;
        // $core->load_configs();

    }
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
