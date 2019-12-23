<?php

namespace Tests\Browser\Settings;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MembersSettingsTest extends DuskTestCase
{
    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "MembersSettingsTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_can_see_members_settings()
    {
        echo "test_user_can_see_members_settings\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/members')
                    ->assertSee('Allow Registration');
        });
    }

    public function test_user_can_change_allow_registration_setting()
    {
        echo "test_user_can_change_allow_registration_setting\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->select('allowRegistrations', 'true')
                    ->press('save')
                    ->pause(2000)
                    ->assertSelected('allowRegistrations', 'true')
                    ->logout()
                    ->pause(1000)
                    ->visit('/auth/register')
                    ->pause(2000)
                    ->assertSee('SIGN UP');
        });

    }

    public function test_user_can_change_display_name_setting()
    {
        echo "test_user_can_change_display_name_setting\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->select('allowRegistrations', 'true')
                    ->press('save')
                    ->pause(2000)
                    ->assertSelected('allowRegistrations', 'true')
                    ->select('userDisplayName', 'username')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->assertSelected('userDisplayName', 'username');
        });
    }

    public function test_user_can_change_default_role_setting()
    {
        echo "test_user_can_change_default_role_setting\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->select('allowRegistrations', 'true')
                    ->press('save')
                    ->pause(2000)
                    ->assertSelected('allowRegistrations', 'true')
                    ->select('defaultUserRole', 'admin')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->assertSelected('defaultUserRole', 'admin');
        });
    }

    public function test_user_can_change_require_full_name_setting()
    {
        echo "test_user_can_change_require_full_name_setting\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->select('allowRegistrations', 'true')
                    ->press('save')
                    ->pause(2000)
                    ->assertSelected('allowRegistrations', 'true')
                    ->select('requireFullname', 'false')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->assertSelected('requireFullname', 'false');
        });
    }

    public function test_user_can_change_registration_status_setting()
    {
        echo "test_user_can_change_registration_status_setting\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->select('allowRegistrations', 'true')
                    ->press('save')
                    ->pause(2000)
                    ->assertSelected('allowRegistrations', 'true')
                    ->select('autoApprove', 'true')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->assertSelected('autoApprove', 'true');
        });
    }
}
