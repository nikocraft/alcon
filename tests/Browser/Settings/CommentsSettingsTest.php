<?php

namespace Tests\Browser\Settings;

use App\Models\User;
use App\Models\Role;
use App\Models\Core\Settings\Website;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CommentsSettingsTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_see_members_settings()
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
                    ->assertSee('Comments Type')
                    ->assertSee('Logged in to Comment')
                    ->assertSee('Nested Comments')
                    ->assertSee('Nested Depth')
                    ->assertSee('Comments Order');
        });
    }
}
