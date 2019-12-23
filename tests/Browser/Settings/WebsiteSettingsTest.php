<?php

namespace Tests\Browser\Settings;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WebsiteSettingsTest extends DuskTestCase
{

    public function setUp(): void
    {
        parent::setUp();
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
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/website')
                    ->assertSee('Website General Settings')
                    ->assertSee('Title')
                    ->assertSee('Tagline')
                    ->assertSee('Site Url');
        });
    }

    public function test_user_can_change_title_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->clear('title')
                    ->type('title', 'Title Test Success!')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/')
                    ->assertSee('Title Test Success!');
        });
    }

    public function test_user_can_change_tagline_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->clear('tagline')
                    ->type('tagline', 'Tagline Test Success!')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->assertInputValue('tagline', 'Tagline Test Success!');
        });
    }

    public function test_user_can_change_url_setting()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->clear('url')
                    ->type('url', 'laraone.com')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->assertInputValue('url', 'laraone.com');
        });
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
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/website')
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
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->select('frontPageType', 'index-page')
                    ->select('frontPageMeta', '2')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
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
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->select('paginationType', 'standard')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
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
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->clear('paginationPerPage')
                    ->type('paginationPerPage', '5')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->assertInputValue('paginationPerPage', '5');
        });
    }
}
