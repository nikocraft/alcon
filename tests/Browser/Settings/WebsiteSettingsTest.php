<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

class WebsiteSettingsTest extends DuskTestCase
{


    protected static $db_inited = false;

    public function setUp(): void
    {
        parent::setUp();

        if (!static::$db_inited) {
            static::$db_inited = true;
            $path = __DIR__ . '../../../sqlite.testing.database';
            file_put_contents($path, '');
            Artisan::call('laraone:install');
        }
    }

    public function test_user_can_see_website_settings()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/website')
                    ->pause(3000)
                    ->assertPathIs('/admin/settings/website')
                    ->assertSee('Website Settings')
                    ->assertSee('Title')
                    ->assertSee('Tagline')
                    ->assertSee('Site Url');
        });
    }
}
