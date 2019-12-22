<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use App\Services\SettingsService;

class FetchCommand extends BaseCommand
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
    protected $description = 'Fetch latest Laraone release from Github repo.';

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
        
        $currentVersion = get_website_setting('cms.phoenix');
        $lastVersion = $this->getPhoenixLastVersion();

        if($currentVersion != $lastVersion) {
            $this->fetchRelease($lastVersion);
            $this->fetchAdminTheme($lastVersion);
            $this->fetchDefaultTheme($lastVersion);
        } else {
            $this->info('Already up to latest release.');
        }

        return $lastVersion;
    }
}
