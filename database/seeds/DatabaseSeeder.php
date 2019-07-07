<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
        $this->call(UsersSeeder::class);

        $this->call(AdminMenuSeeder::class);
        $this->call(WebSiteSettingsSeeder::class);
        $this->call(ContentTypeSeeder::class);
        $this->call(AdminThemeSeeder::class);
        $this->call(DefaultThemeSeeder::class);
        $this->call(TaxonomiesTableSeeder::class);
    }
}
