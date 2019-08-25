<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use App\Services\WebsiteService;
use App\Services\Zip\ZipArchive;

class Update extends Releases
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:update {--fetch-latest}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Laraone. New code should either be pulled by git or fetch command before update is run.';

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
        $currentVersion = get_website_setting('laraone.phoenix');

        Artisan::call('config:clear');
        Artisan::call('config:cache');
        
        if($this->option('fetch-latest')) {
            $this->fetchLatestRelease();
            // exec('composer dump-autoload');
            $zip = new ZipArchive;
            $lastVersion = $this->getLastVersion();

            if ($zip->open(storage_path('releases'. DIRECTORY_SEPARATOR . $lastVersion . '.zip')) === TRUE) {
                $base = 'phoenix-' . $lastVersion . DIRECTORY_SEPARATOR;
                $this->info('Unpacking latest release.');
                $zip->extractSubdirTo($base .'app', base_path('app'));
                $zip->extractSubdirTo($base .'database', base_path('database'));
                $zip->extractSubdirTo($base .'resources', base_path('resources'));
                $zip->extractSubdirTo($base .'routes', base_path('routes'));
                $zip->extractSubdirTo($base .'config', base_path('config'));
                $zip->extractSubdirTo($base .'public' . DIRECTORY_SEPARATOR . 'install', base_path('public' . DIRECTORY_SEPARATOR . 'install'));
            } else {
                $this->info('Not able to open release zip and proceed with the update. Please report this problem.');
            }
        }

        $currentReleaseIndex = $this->getReleaseIndex($currentVersion);
        $lastReleaseIndex = $this->getLastIndex();

        if($currentReleaseIndex != $lastReleaseIndex) {
            $this->info('Update started, current version is ' . $currentVersion);

            $updatesData = array_slice($this->releasesData, $currentReleaseIndex);
            $seeded = 0;

            foreach($updatesData as $key => $value) {
                Artisan::call('migrate', [
                    '--force' => true,
                ]);
                $this->info(Artisan::output());
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
            $websiteService->updateSetting('laraone', 'phoenix', $this->getLastVersion());
            $this->info('Laraone has been updated successfully to ' . $this->getLastVersion());
        } else {
            $this->info('Already up to date.');
        }
    }

    protected function fetchLatestRelease()
    {
        $this->call('laraone:fetch');
    }

    protected function getSeedFileName($version)
    {
        $seedFileName = 'Seed' . preg_replace("/[^a-zA-Z0-9]/", "", $version);
        return $seedFileName;
    }
}
