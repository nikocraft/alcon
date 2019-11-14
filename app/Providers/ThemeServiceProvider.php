<?php

namespace App\Providers;

/**
 * This service provider should be executed AFTER 'Laraone\Themes\themeServiceProvider' 
 * to override it and make it work with modules/extensions to LaraOne
 */


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use ThemeManager;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /*--------------------------------------------------------------------------
        | Replace FileViewFinder
        |--------------------------------------------------------------------------*/

        // \App::singleton('view.finder', function($app)
        // {
        //     return new \App\Services\ThemeViewFinderService(
        //         $app['files'],
        //         $app['config']['view.paths'],
        //         null
        //     );
        // });

        /*--------------------------------------------------------------------------
        | Register helpers.php functions
        |--------------------------------------------------------------------------*/

        require_once __DIR__.'/../Helpers/theme_helpers.php';

    }
}
