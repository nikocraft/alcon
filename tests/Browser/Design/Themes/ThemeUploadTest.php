<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ThemeUploadTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    // In order for this test to pass, make sure theme .zip file is present in the current folder

    public function test_user_uploads_and_activates_theme() {

        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/design/themes')
                    ->pause(2000)
                    ->assertPathIs('/admin/design/themes')
                    ->click('#tab-upload')
                    ->pause(200)
                    ->attach('#file', base_path('tests/Browser/Design/Themes/aurora.zip'))
                    ->pause(6000)
                    ->click('#tab-installed')
                    ->pause(2600)
                    ->click('#pane-installed > div > div:nth-child(2) > div')
                    ->pause(1000)
                    ->press('Activate')
                    ->pause(200)
                    ->visit('/')
                    ->pause(1000)
                    ->assertPresent('#sidebar-content')
                    ->assertSee('Website tagline...');
        });
    }
}
