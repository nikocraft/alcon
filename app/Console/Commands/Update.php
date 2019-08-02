<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use App\Services\WebsiteService;

class Update extends Releases
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:update';

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

        $this->releasesData = $this->getReleasesData();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $websiteService = new WebsiteService();
        $websiteSettings = $websiteService->getSettings();
        $currentVersion = '1.0.0-beta.1'; // data_get($websiteSettings, 'laraone.phoenix');

        $currentIndex = $this->getCurrentIndex($currentVersion);
        $lastIndex = $this->getLastIndex();

        if($currentIndex != $lastIndex) {
            $this->info('Update started, current version is ' . $currentVersion);

            $updatesData = array_slice($this->releasesData, $currentIndex);
            $seeded = 0;

            foreach($updatesData as $key => $value) {
                $seedFile = $this->getSeedFileName($value['version']);
                if(file_exists(base_path('database/seeds/updates/' . $seedFile . '.php'))) {
                    Artisan::call('db:seed', [
                        '--class' => '\Database\Seeds\Updates\\' . $seedFile,
                        '--force' => true,
                    ]);
                    // $seeded += 1;
                    $this->info('Seeded ' . $seedFile);
                } else {
                    $this->info('Seed file not found.');
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

    protected function getSeedFileName($version)
    {
        $seedFileName = 'Seed' . preg_replace("/[^a-zA-Z0-9]/", "", $version);
        return $seedFileName;
    }
}
