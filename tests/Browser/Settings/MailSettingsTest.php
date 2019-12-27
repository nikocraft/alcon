<?php

namespace Tests\Browser\Settings;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Traits\EmailTrait;

class MailSettingsTest extends DuskTestCase
{

    use Emailtrait;

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

        $this->deleteEmails();

        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {

            // Add false mail data

            $browser->loginAs($user)
                    ->visit('/admin/settings/mail')
                    ->pause(3200)
                    ->assertPathIs('/admin/settings/mail')
                    ->type('fromAddress', 'supertest@gmail.com')
                    ->select('driver', 'smtp')
                    ->type('host', '127.0.0.1')
                    ->type('port', '80')
                    ->press('Save')
                    ->pause(3000)

            // Check if the data persists

                    ->visit('/admin/settings/mail')
                    ->pause(3200)
                    ->assertInputValue('fromAddress', 'supertest@gmail.com')
                    ->assertSelected('driver', 'smtp')
                    ->assertInputValue('host', '127.0.0.1')
                    ->assertInputValue('port', '80')
                    ->logout()

            // Try password reset and fail

                    ->visit('/auth/password/reset')
                    ->type('email', 'admin@gmail.com')
                    ->press('#auth > div > div.box-body > div > div.auth-button > button')
                    ->pause(34600)
                    ->assertSee('Connection to 127.0.0.1:80 Timed Out')

            // Add true mail data

                    ->loginAs($user)
                    ->visit('/admin/settings/mail')
                    ->pause(3000)
                    ->assertPathIs('/admin/settings/mail')
                    ->type('fromAddress', 'supertest@gmail.com')
                    ->select('driver', 'smtp')
                    ->type('host', 'localhost')
                    ->type('port', '1025')
                    ->press('Save')
                    ->pause(3000)
                    ->logout()

            // Try password reset and succeed

                    ->visit('/auth/password/reset')
                    ->type('email', 'admin@gmail.com')
                    ->press('#auth > div > div.box-body > div > div.auth-button > button')
                    ->pause(5200)
                    ->assertSee('An email with instructions on how to reset your password should be arriving soon.')
                    ->visit($this->getUrlFromEmail())->assertPresent('.button-primary')
                    ->pause(1500);

                    // Trick to avoid _blank
                    $urlConfirm = $browser->element('.button-primary')->getAttribute('href');
                    $browser->visit($urlConfirm)->screenshot('confirmation_reset_email')->assertSee('RESET YOUR PASSWORD');
        });
    }
}
