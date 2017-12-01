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
            __DIR__.'/config/manifold.php' => config_path('manifold.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Refresh::class,
                Check::class,
                Env::class,
            ]);
        }

        $core = new Core;
        //normal boot
        $core->load_configs();
        //forcing reload of fresh data
        // $core->refresh();

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
