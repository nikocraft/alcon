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
        $cmsCurrentVersion = get_website_setting('cms.version');
        $lastVersion = $this->updateService->getLastVersion();

        Artisan::call('config:clear');
        Artisan::call('config:cache');
        // $this->info('composer dump-autoload');
        // exec('composer dump-autoload');

        $currentReleaseIndex = $this->updateService->getReleaseIndex($cmsCurrentVersion);
        $lastReleaseIndex = $this->updateService->getLastIndex();

        if($currentReleaseIndex != $lastReleaseIndex) {
            $this->info('Update started, current version is ' . $cmsCurrentVersion);

            $updatesData = array_slice($this->releasesData, $currentReleaseIndex);
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

            $this->info('LaraOne updated to v' . $lastVersion);
        } else {
            $this->info('LaraOne is already up to date!');
        }

        if($this->option('update-active-theme')) {
            $this->updateService->fetchAndUpdateActiveTheme($lastVersion);
        }

        $websiteService->updateSetting('cms.version', '1.2.1');

        $this->info('LaraOne updated!');
    }

    protected function getSeedFileName($version)
    {
        $seedFileName = 'Seed' . preg_replace("/[^a-zA-Z0-9]/", "", $version);
        return $seedFileName;
    }
}
