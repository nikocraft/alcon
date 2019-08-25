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
    protected $signature = 'laraone:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Laraone Release from Github.';

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
        $this->fetchLatestReleaseData();
        
        $currentVersion = get_website_setting('laraone.phoenix');
        $lastVersion = $this->getLastVersion();

        if($currentVersion != $lastVersion) {
            $this->fetchRelease($lastVersion);
            $this->fetchDefaultTheme($lastVersion);
        } else {
            $this->info('Already up to latest release.');
        }

        return $lastVersion;
    }
}
