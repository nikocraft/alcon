<?php

namespace Tests\Browser\Settings;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ContentSettingsTest extends DuskTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_see_content_settings()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/content')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/content')
                    ->assertSee('What should we show on frontpage?')
                    ->assertSee('Pagination Type')
                    ->assertSee('Items Per Page');
        });
    }

    public function test_user_can_change_frontpage_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/content')
                    ->pause(2000)
                    ->select('frontPageType', 'index-page')
                    ->select('frontPageMeta', '2')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/content')
                    ->pause(2000)
                    ->assertSelected('frontPageType', 'index-page')
                    ->assertSelected('frontPageMeta', '2');
        });
    }

    public function test_user_can_change_pagination_type_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/content')
                    ->pause(2000)
                    ->select('paginationType', 'standard')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/content')
                    ->pause(2000)
                    ->assertSelected('paginationType', 'standard');
        });
    }

    public function test_user_can_change_items_per_page_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/content')
                    ->pause(2000)
                    ->clear('paginationPerPage')
                    ->type('paginationPerPage', '5')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/content')
                    ->pause(2000)
                    ->assertInputValue('paginationPerPage', '5');
        });
    }
}
