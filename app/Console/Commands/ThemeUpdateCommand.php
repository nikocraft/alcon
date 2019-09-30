<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WebsiteService;
use App\Services\Themes\ThemeService;
use App\Services\Zip\ZipArchive;

class ThemeUpdateCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:theme-update {--theme=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update specific Laraone theme.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $themeName = $this->option('theme');

        $websiteService = new WebsiteService;
        $phoenixVersion = get_website_setting('laraone.phoenix');

        $themeService = new ThemeService;
        $theme = $themeService->getThemeByFolderName($themeName);

        $this->fetchTheme($phoenixVersion, $theme->releases_url, $theme->download_url, $themeName . '.zip');
        $themePath = storage_path('themes'. DIRECTORY_SEPARATOR . $themeName . '.zip');
        $return = $themeService->updateTheme($themePath);

        if($return->code == 200) {
            $this->info($return->message);
        } else {
            $this->error('Updating the theme failed.');
            $this->error($return->message);
        }
    }
}
