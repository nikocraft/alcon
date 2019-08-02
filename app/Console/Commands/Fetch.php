<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use App\Services\WebsiteService;

class Fetch extends Releases
{
    /**
     * Fetch zip file for cms, can specify the version.
     *
     * @var string
     */
    protected $signature = 'laraone:fetch {--latest} {--next}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Laraone zip files, used by update command to update the CMS.';

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

        $this->info('version: ' . $currentVersion);
    }
}
