<?php

namespace Tests\Browser\Settings;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthSettingsTest extends DuskTestCase
{
    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "RolesTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_modifies_text_logo_on_auth_page()
    {
        echo "test_user_modifies_text_logo_on_auth_page\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/auth')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/auth')
                    ->select('logoType', 'text')
                    ->type('logoText', 'Testing')
                    ->press('Save')
                    ->pause(2000)
                    ->logout()
                    ->visit('/auth/login')
                    ->pause(2000)
                    ->assertSee('Testing');
        });
    }

    public function test_user_modifies_image_logo_on_auth_page()
    {
        echo "test_user_modifies_image_logo_on_auth_page\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);


        $this->browse(function (Browser $browser) use ($user) {
            $day = date("d");
            $month = date('m');
            $browser->loginAs($user)
                    ->visit('/admin/settings/auth')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/auth')
                    ->select('logoType', 'image')
                    ->click('#app > div > div > section > div > div > div > div.box-body.noselect > div:nth-child(2) > div > span')
                    ->pause(800)
                    ->click('#tab-upload')
                    ->pause(200)
                    ->attach('#file', base_path('public/themes/ikigai/images/screenshot.jpg'))
                    ->pause(4000)
                    ->click('#pane-library > div.el-tab-pane_items-container > section > div.b-masonry-list > div')
                    ->press('Insert Image')
                    ->pause(400)
                    ->press('Save')
                    ->pause(2000)
                    ->logout()
                    ->visit('/auth/login')
                    ->pause(2000)
                    ->assertSourceHas('<img src="/uploads/2019/'. $month . '/' . $day . '/screenshot.jpg" class="logo-image img-responsive">');
        });
    }
}
