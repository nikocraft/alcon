<?php

use Illuminate\Database\Seeder;

class InstallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder should not be called manually, it's used by the installer
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
        $this->call(AdminMenuSeeder::class);
        $this->call(WebSiteSettingsSeeder::class);
        $this->call(ContentTypeSeeder::class);
        $this->call(AdminThemeSeeder::class);
        $this->call(DefaultThemeSeeder::class);
        $this->call(TaxonomiesTableSeeder::class);
    }
}
