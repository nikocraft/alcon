<?php

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
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
        $this->call(AdminUserSeeder::class);
        $this->call(AdminMenuSeeder::class);
        $this->call(WebSiteSettingsSeeder::class);
        $this->call(ContentTypeSeeder::class);
        $this->call(AdminThemeSeeder::class);
        $this->call(DefaultThemeSeeder::class);
        $this->call(TaxonomiesTableSeeder::class);
    }
}
