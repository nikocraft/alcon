<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MailSettingsTest extends DuskTestCase
{
    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "MailSettingsTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_can_see_mail_settings()
    {
        echo "test_user_can_see_mail_settings\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/mail')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/mail')
                    ->assertSee('Mail Settings');
        });
    }

    public function test_user_can_change_mail_settings()
    {
        echo "test_user_can_change_mail_settings\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/mail')
                    ->pause(3000)
                    ->assertPathIs('/admin/settings/mail')
                    ->type('fromAddress', 'supertest@gmail.com')
                    ->select('driver', 'smtp')
                    ->type('host', '127.0.0.1')
                    ->type('port', '80')
                    ->press('Save')
                    ->pause(3000)
                    ->visit('/admin/settings/mail')
                    ->pause(3000)
                    ->assertInputValue('fromAddress', 'supertest@gmail.com')
                    ->assertSelected('driver', 'smtp')
                    ->assertInputValue('host', '127.0.0.1')
                    ->assertInputValue('port', '80');
        });
        $this->assertEquals(env('MAIL_FROM_ADDRESS', null), 'supertest@gmail.com');
        $this->assertEquals(env('MAIL_DRIVER', null), 'smtp');
        $this->assertEquals(env('MAIL_HOST', null), '127.0.0.1');
        $this->assertEquals(env('MAIL_PORT', null), '80');

    }
}
