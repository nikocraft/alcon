<?php

namespace App\Providers;

use DB;
use Schema;
use App\Models\Core\Settings\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        if($this->testDbConnection()
            && Schema::hasTable( (new Setting)->getTable() )
        ) {
            $this->mapSpaRoutes();
            $this->mapAuthRoutes();
            $this->mapWebRoutes();
        } else {
            $this->mapAuthRoutes();
            $this->mapInstallerRoutes();
        }
    }

    /**
     * Define the "auth" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/auth.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "spa" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapSpaRoutes()
    {
        Route::prefix('api')
             ->middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/spa.php'));
    }

    /**
     * Define the "installer" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapInstallerRoutes()
    {
        Route::prefix('installer')
             ->namespace($this->namespace)
             ->group(base_path('routes/installer.php'));
    }

    /**
     * Method to test database is there and accessable
     *
     * @return void
     */
    private function testDbConnection()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
