<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginSettingsTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_see_login_settings()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/login')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/login')
                    ->assertSee('Login Customization')
                    ->assertSee('Logo')
                    ->assertSee('Logo Text');
        });
    }

    public function test_user_can_change_login_logo_text()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/login')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/login')
                    ->type('logoText', 'Testing Logo Text')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/login')
                    ->pause(2000)
                    ->assertInputValue('logoText', 'Testing Logo Text')
                    ->logout()
                    ->visit('/auth/login')
                    ->pause(1000)
                    ->assertSee('Testing Logo Text');
        });
    }
}
