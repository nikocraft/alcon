<?php

use Illuminate\Database\Seeder;
use App\Services\SettingsService;

class DatabaseSeeder extends Seeder
{
    protected $websiteService;

    public function __construct(SettingsService $websiteService)
    {
        $this->websiteService = $websiteService;
    }
    /**
     * Run the database seeds.
     * This seeder seeds Faker user data, used for local development
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
        $this->call(AdminMenuSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(ContentTypeSeeder::class);
        $this->call(TaxonomiesTableSeeder::class);
        $this->call(DefaultThemeSeeder::class);

        $this->websiteService->updateSetting('cms.installed', true);
    }
}
