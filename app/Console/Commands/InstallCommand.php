<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use Config;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Services\SettingsService;
use App\Services\ThemeService;

class InstallCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraone:install {--force} {--create-user} {--artisan-output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations & seeds, fetch themes from github repos.';

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
        $cmsLastVersion = $this->updateService->getLastVersion();

        if($this->option('create-user')) {
            $username = $this->askWithValidation('Enter username', null, function ($value) {
                return $this->validateInput('username', 'alpha|min:4|max:30', $value);
            });

            $email = $this->askWithValidation('Enter valid email', null, function ($value) {
                return $this->validateInput('email', 'email', $value);
            });

            $password = $this->askWithValidation('Enter secure password', null, function ($value) {
                return $this->validateInput('password', 'required|min:8', $value);
            });

            $firstname = $this->askWithValidation('Enter firstname', null, function ($value) {
                return $this->validateInput('firstname', 'required|alpha|min:3', $value);
            });

            $lastname = $this->askWithValidation('Enter lastname', null, function ($value) {
                return $this->validateInput('lastname', 'required|alpha|min:3', $value);
            });
        }

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        $this->info('Generating new app key');
        Artisan::call('key:generate', [ '--force' => true ]);

        $this->info('About to download default frontend theme.');
        // $this->updateService->fetchDefaultTheme($cmsLastVersion);

        $this->info('Running migrations.');
        Artisan::call('migrate:fresh', [ '--force' => true ]);

        if($this->option('artisan-output'))
            $this->info(Artisan::output());

        $this->info('Running seeds.');

        Artisan::call('db:seed', [
            '--class' => 'DatabaseSeeder',
            '--force' => true,
        ]);

        if($this->option('create-user')) {
            // get super-admin role
            $super = Role::find(1);

            // create first user
            $user = User::create([
                'username' => $username,
                'firstname' => $firstname ? $firstname : 'Admin',
                'lastname' => $lastname ? $lastname : 'User',
                'slug' => $username,
                'email' => $email,
                'password' => bcrypt($password),
                'activated' => true,
                'activated_at' => Carbon::now(),
                'approved' => true
            ]);

            // attach super role to the user
            $user->attachRole($super);

        } else {
            $environment = env('APP_ENV', 'production');
            switch ($environment) {
                case 'local':
                    Artisan::call('db:seed', [
                        '--class' => 'DummyUserSeeder',
                        '--force' => true,
                    ]);
                    break;
                case 'production':
                    Artisan::call('db:seed', [
                        '--class' => 'AdminUserSeeder',
                        '--force' => true,
                    ]);
                    break;
                default:
                    # code...
                    break;
            }
        }

        if($this->option('artisan-output')) {
            $this->info(Artisan::output());
        }

        $settingsService = new SettingsService;
        $settingsService->updateSetting('cms.version', $cmsLastVersion);

        $this->info('Laraone CMS v.' . $cmsLastVersion . ' successfully installed!');
    }
}
