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
    protected $description = 'Update CMS';

    /**
     * Release data, so we can install / update
     *
     * @var string
     */
    private $releasesData;

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

        $currentIndex = $this->findCurrentIndex($currentVersion);
        $lastIndex = $this->getLastIndex();


        $seed = 'Seed' . preg_replace("/[^a-zA-Z0-9]/", "", $currentVersion);

        $this->info('res ' . $seed);

        if($currentIndex != $lastIndex) {
            // $this->info('Update started, current version is ' . $currentVersion);

            // $updateArray = array_slice($this->releasesData, $currentIndex);
            // $this->info('Seeding new data.');
            // $seeded = 0;

            // foreach($updateArray as $key => $value) {
            //     if(file_exists(base_path() . DIRECTORY_SEPARATOR . 'laraone.json' )) {
            //         Artisan::call('db:seed', [
            //             '--class' => '\Database\Seeds\Updates\\' . $value['class'],
            //             '--force' => true,
            //         ]);
            //         $seeded += 1;
            //     }
            // }
            // if($seeded) {
            //     $this->info('Seeding completed.');
            // } else {
            //     $this->info('Nothing new to seed.');
            // }
            // exec('composer dump-autoload');
            // $websiteService->updateSetting('laraone', 'phoenix', $this->getLastVersion());
            // $this->info('Laraone has been updated successfully to ' . $this->getLastVersion());
        } else {
            $this->info('Already up to date.');
        }
    }
}
