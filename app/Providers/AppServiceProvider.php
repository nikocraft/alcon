<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Laravel\Dusk\DuskServiceProvider;
use App\Services\SettingsService;
use App\Services\GlobalSettingsService;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('helpers.welcome', 'welcome');
        Blade::component('helpers.taxonomy');
        Blade::include('helpers.style-renderer', 'style');
        Blade::include('helpers.css-value-renderer', 'css_value');

        Relation::morphMap([
            'content' => 'App\Models\Core\Content\Content',
            'widgets' => 'App\Models\Core\Design\Widget',
            'templates' => 'App\Models\Core\Content\Template',
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }

        $this->app->singleton(GlobalSettingsService::class, function ($app) {
            return new GlobalSettingsService();
        });

        require_once __DIR__.'/../Services/helpers.php';
    }
}
