<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use App\Services\SettingsService;
use App\Services\ThemeService;
use App\Services\ZipArchiveService;

class UpdateCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:update {--update-active-theme}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Laraone by running new migrations and seeds. Latest code should be pulled by git before running this command.';

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
        $websiteService = new SettingsService;
        $themeService = new ThemeService;
        $phoenixCurrentVersion = get_website_setting('cms.phoenix');
        $phoenixLastVersion = $this->updateService->getPhoenixLastVersion();

        Artisan::call('config:clear');
        Artisan::call('config:cache');
        // $this->info('composer dump-autoload');
        // exec('composer dump-autoload');

        $phoenixCurrentReleaseIndex = $this->updateService->getReleaseIndex($phoenixCurrentVersion);
        $phoenixLastReleaseIndex = $this->updateService->getLastIndex();

        if($phoenixCurrentReleaseIndex != $phoenixLastReleaseIndex) {
            $this->info('Update started, current version is ' . $phoenixCurrentVersion);

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

            $this->info('Phoenix updated to v' . $phoenixLastVersion);
        } else {
            // fetch admin and active theme
            $this->info('Phoenix already up to date!');
        }

        $this->updateService->fetchAndUpdateAdminTheme($phoenixLastVersion);

        if($this->option('update-active-theme')) {
            $this->updateService->fetchAndUpdateActiveTheme($phoenixLastVersion);
        }
        $adminTheme = $themeService->getThemeByFolderName('atlas');
        $this->info('Phoenix: v' . $phoenixLastVersion . ', Atlas: v' .  $adminTheme->version);

        $websiteService->updateSetting('cms.phoenix', $phoenixLastVersion);
        $websiteService->updateSetting('cms.atlas', $adminTheme->version);

        $this->info('Laraone updated!');
    }

    protected function getSeedFileName($version)
    {
        $seedFileName = 'Seed' . preg_replace("/[^a-zA-Z0-9]/", "", $version);
        return $seedFileName;
    }
}
