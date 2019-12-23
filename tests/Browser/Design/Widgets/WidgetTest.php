<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WidgetTest extends DuskTestCase
{
    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "WidgetTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_can_add_new_widget_on_sidebar()
    {
        echo "test_user_can_add_new_widget_on_sidebar\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/design/customize')
                    ->pause(2000)
                    ->assertPathIs('/admin/design/customize')
                    ->click('#tab-content')
                    ->click('#pane-content > div > div:nth-child(2) > div > div.form-group-sub > div:nth-child(1) > div > div.form-group-sub > div > div > div > div > div:nth-child(3)')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->select('frontPageType', 'index-page')
                    ->select('frontPageMeta', '2')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/design/widgets/create')
                    ->pause(2000)
                    ->assertPathIs('/admin/design/widgets/create')
                    ->type('title', 'Test Widget')
                    ->select('#app > div > div > section > div > div > div > div > div.box-body > div:nth-child(2) > select', 'sidebar')
                    ->press('Headline')
                    ->type('#pane-content > div > div.widgets_container_wrapper > div > div > div > div.content-block-body > h2', 'Testing the widget')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/')
                    ->pause(1000)
                    ->assertSee('Testing the widget');
        });
    }
}
