<?php

namespace Tests\Browser\Settings;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CommentsSettingsTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_see_comments_settings()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->assertSelected('type', 'off');
        });
    }

    public function test_user_can_change_logged_in_to_comment_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'native')
                    ->pause(200)
                    ->assertSelected('type', 'native')
                    ->select('loggedInToComment', 'true')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertSelected('loggedInToComment', 'true');
        });
    }

    public function test_user_can_change_nested_comments_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'native')
                    ->pause(200)
                    ->assertSelected('type', 'native')
                    ->select('allowNested', 'false')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertSelected('allowNested', 'false');
        });
    }

    public function test_user_can_change_nested_depth_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'native')
                    ->select('allowNested', 'true')
                    ->pause(200)
                    ->assertSelected('type', 'native')
                    ->assertSelected('allowNested', 'true')
                    ->select('nestedDepth', '2')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertSelected('nestedDepth', '2');
        });
    }

    public function test_user_can_change_comments_order_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'native')
                    ->pause(200)
                    ->assertSelected('type', 'native')
                    ->select('order', 'desc')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertSelected('order', 'desc');
        });
    }

    public function test_user_can_change_comments_type_setting_to_off()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'off')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertSelected('type', 'off');
        });
    }

    public function test_user_can_change_comments_type_setting_to_disqus()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'disqus')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertSelected('type', 'disqus');
        });
    }

    public function test_user_can_change_disqus_channel_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'disqus')
                    ->pause(200)
                    ->assertSelected('type', 'disqus')
                    ->type('disqusChannel', 'disqusTesting')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertInputValue('disqusChannel', 'disqusTesting');
        });
    }
}
