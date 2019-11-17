<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

use App\Services\ThemeService;
use App\Services\WebsiteService;

class DefaultThemeSeeder extends Seeder
{
    protected $themeservice;
    protected $websiteService;

    public function __construct(ThemeService $themeservice, WebsiteService $websiteService)
    {
        $this->themeservice = $themeservice;
        $this->websiteService = $websiteService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $themePath = storage_path('themes'. DIRECTORY_SEPARATOR . config('laraone.default_theme.name') . '.zip');
        $return = $this->themeservice->installTheme($themePath);

        $this->command->info($return->message);

        if($return->code == 422) {
            $this->command->info('Seeding aborted, theme install failed for some reason.');
            dd();
        }
    }
}
