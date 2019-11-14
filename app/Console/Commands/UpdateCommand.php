<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use App\Services\WebsiteService;
use App\Services\ThemeService;
use App\Services\ZipArchiveService;

class UpdateCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:update {--fetch-latest-phoenix} {--fetch-active-theme}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Laraone by running new migrations and seeds. Latest version should be pulled by git before running this command. Option --fetch-latest-phoenix should only be used if not using git to deploy.';

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
        $websiteService = new WebsiteService;
        $themeService = new ThemeService;
        $phoenixCurrentVersion = get_website_setting('laraone.phoenix');
        $phoenixLastVersion = $this->getPhoenixLastVersion();

        Artisan::call('config:clear');
        Artisan::call('config:cache');
        // $this->info('composer dump-autoload');
        // exec('composer dump-autoload');

        $phoenixCurrentReleaseIndex = $this->getReleaseIndex($phoenixCurrentVersion);
        $phoenixLastReleaseIndex = $this->getLastIndex();

        if($phoenixCurrentReleaseIndex != $phoenixLastReleaseIndex) {
            $this->info('Update started, current version is ' . $phoenixCurrentVersion);

            if($this->option('fetch-latest-phoenix')) {
                $this->fetchLatestRelease();
                $zip = new ZipArchiveService;
    
                if ($zip->open(storage_path('releases'. DIRECTORY_SEPARATOR . $phoenixLastVersion . '.zip')) === TRUE) {
                    $base = 'phoenix-' . $phoenixLastVersion . DIRECTORY_SEPARATOR;
                    $this->info('Unpacking latest release.');
                    $zip->extractSubdirTo($base .'app', base_path('app'));
                    $zip->extractSubdirTo($base .'database', base_path('database'));
                    $zip->extractSubdirTo($base .'resources', base_path('resources'));
                    $zip->extractSubdirTo($base .'routes', base_path('routes'));
                    $zip->extractSubdirTo($base .'config', base_path('config'));
                    $zip->extractSubdirTo($base .'public' . DIRECTORY_SEPARATOR . 'install', base_path('public' . DIRECTORY_SEPARATOR . 'install'));
                    exec('composer dump-autoload');
                    $this->info('Successfully unpacked the release.');
                } else {
                    $this->info('Not able to unpack zip and proceed with the update. Please report this problem.');
                }
            }

            $updatesData = array_slice($this->releasesData, $phoenixCurrentReleaseIndex);
            $seeded = 0;

            Artisan::call('migrate', [
                '--force' => true,
            ]);
            $this->info(Artisan::output());

            foreach($updatesData as $key => $value) {
                $seedFile = $this->getSeedFileName($value['version']);
                if(file_exists(base_path('database/seeds/updates/' . $seedFile . '.php'))) {
                    Artisan::call('db:seed', [
                        '--class' => '\Database\Seeds\Updates\\' . $seedFile,
                        '--force' => true,
                    ]);
                    $seeded += 1;
                    $this->info('Seeded ' . $seedFile);
                }
            }
            if($seeded) {
                $this->info('Seeding completed.');
            } else {
                $this->info('Nothing new to seed.');
            }
            // exec('composer dump-autoload');

            // fetch admin and active theme
            // $this->fetchThemes($phoenixLastVersion);

            // update versions
            $websiteService->updateSetting('laraone', 'phoenix', $phoenixLastVersion);
            

            $this->info('Phoenix updated to v' . $phoenixLastVersion);
        } else {
            // fetch admin and active theme
            $this->info('Phoenix already up to date!');
        }

        $this->fetchAdmin($phoenixLastVersion);
        if($this->option('fetch-active-theme')) {
            $this->fetchActiveTheme($phoenixLastVersion);
        }
        $adminTheme = $themeService->getThemeByFolderName('admin');
        $websiteService->updateSetting('laraone', 'admin', $adminTheme->version);
        $this->info('Phoenix: v' . $phoenixLastVersion . ', SPA Admin: v' .  $adminTheme->version);
        $this->info('Laraone updated!');
    }

    private function fetchAdmin($phoenixLastVersion)
    {
        $themeService = new ThemeService;
        // download admin theme and update
        $adminTheme = $themeService->getThemeByFolderName('admin');
        $adminThemeId = $adminTheme->id;
        $this->fetchAndUpdateTheme($phoenixLastVersion, $adminThemeId);
    }

    private function fetchActiveTheme($phoenixLastVersion)
    {
        // download active theme and update
        $activeThemeId = get_website_setting('website.activeTheme');
        $this->fetchAndUpdateTheme($phoenixLastVersion, $activeThemeId);
    }

    private function fetchThemes($phoenixLastVersion)
    {
        $themeService = new ThemeService;

        // download admin theme and update
        $adminTheme = $themeService->getThemeByFolderName('admin');
        $adminThemeId = $adminTheme->id;
        $this->fetchAndUpdateTheme($phoenixLastVersion, $adminThemeId);

        // download active theme and update
        $activeThemeId = get_website_setting('website.activeTheme');
        $this->fetchAndUpdateTheme($phoenixLastVersion, $activeThemeId);
    }

    private function fetchAndUpdateTheme($phoenixLastVersion, $themeId)
    {
        $themeService = new ThemeService;
        $theme = $themeService->getTheme($themeId);
        $themeFileName = $theme->folder . '.zip';
        $fetch = $this->fetchTheme($phoenixLastVersion, $theme->releases_url, $theme->download_url, $theme->folder);
        if($fetch) {
            $themePath = storage_path('themes'. DIRECTORY_SEPARATOR . $themeFileName);
            $return = $themeService->updateTheme($themePath);
            $this->info($return->message);
        }
    }

    protected function fetchLatestRelease()
    {
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        $this->fetchLatestReleaseData();
        $phoenixLastVersion = $this->getPhoenixLastVersion();
        $this->fetchRelease($phoenixLastVersion);
    }

    protected function getSeedFileName($version)
    {
        $seedFileName = 'Seed' . preg_replace("/[^a-zA-Z0-9]/", "", $version);
        return $seedFileName;
    }
}
