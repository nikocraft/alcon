<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use Config;

class Install extends Releases
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:install {--artisan-output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Laraone CMS';

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
        $phoenixVersion = $this->getLastVersion();
        $this->info('last version: ' . $phoenixVersion);

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        $this->info('About to download admin and default theme.');
        $this->fetchAdminTheme($phoenixVersion);
        $this->fetchDefaultTheme($phoenixVersion);
        $this->info('Both themes downloaded.');

        $this->info('Running migrations.');
        Artisan::call('migrate:fresh', [
            '--force' => true,
        ], null, null);

        if($this->option('artisan-output'))
            $this->info(Artisan::output());

        $this->info('Running seeds.');

        $environment = env('APP_ENV', 'production');

        $seedClass = 'ProductionSeeder';

        if($environment == 'local') {
            $seedClass = 'LocalSeeder';
        }

        Artisan::call('db:seed', [
            '--class' => $seedClass,
            '--force' => true,
        ]);

        if($this->option('artisan-output'))
            $this->info(Artisan::output());

        $this->info('Laraone v' . $phoenixVersion . ' installed successfully!');
    }
}
