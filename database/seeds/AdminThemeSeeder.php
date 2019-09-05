<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Services\Zip\ZipArchive;

use App\Services\Themes\ThemeService;

class AdminThemeSeeder extends Seeder
{
    protected $themeservice;

    public function __construct(ThemeService $themeservice)
    {
        $this->themeservice = $themeservice;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $themePath = storage_path('themes'. DIRECTORY_SEPARATOR . 'admin.zip');
        $saveToDb = false; // admin theme is not saved in themes table, only frontend themes
        $return = $this->themeservice->installTheme($themePath, $saveToDb);

        $this->command->info($return->message);
        
        if($return->code == 422) {
            $this->command->info('Seeding aborted, theme install failed for some reason.');
            dd();
        }
    }
}
