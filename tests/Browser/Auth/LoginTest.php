<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\EmailTrait;

class LoginTest extends DuskTestCase
{
    use EmailTrait;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "LoginTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_can_login_and_logout()
    {
        echo "test_user_can_login_and_logout\r\n";

        $user = User::where('email', 'super@gmail.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/auth/login')
                    ->pause(400)
                    ->type('email', 'super@gmail.com')
                    ->type('password', '12345678')
                    ->press('#auth > div > div.box-body > div > div.auth-button > button')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/pages')
                    ->assertSee('Pages')
                    ->assertAuthenticatedAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(800)
                    ->mouseover('#app > header > div > div > div.notifications-area > div > a')
                    ->click('#app > header > div > div > div.notifications-area > div > div > div > a')
                    ->pause(200)
                    ->visit('/admin/content/pages')
                    ->pause(600)
                    ->assertPathIs('/auth/login');
        });
    }

    public function test_guest_cannot_view_admin_page()
    {
        echo "test_guest_cannot_view_admin_page\r\n";
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/content/pages')
                    ->pause(200)
                    ->assertPathIs('/auth/login');
        });
    }

    public function test_user_enters_wrong_password_and_fails_logging_in() {
        echo "test_user_enters_wrong_password_and_fails_logging_in\r\n";
        $this->browse(function (Browser $browser) {
            $browser->visit('/auth/login')
                    ->type('email', 'super@gmail.com')
                    ->type('password', '123456888')
                    ->press('.btn-auth')
                    ->pause(200)
                    ->assertPathIs('/auth/login');
        });
    }

    public function test_non_existing_user_fails_reseting_password() {
        echo "test_non_existing_user_fails_reseting_password\r\n";
        $this->browse(function (Browser $browser) {
            $browser->visit('/auth/login')
                    ->click('a[href="/auth/password/reset"]')
                    ->type('email', 'example@example.com')
                    ->press('.btn-auth')
                    ->pause(2200)
                    ->assertSee("We can't find a user with that e-mail address.");
        });
    }

    public function test_user_successfully_resets_password() {
        echo "test_user_successfully_resets_password\r\n";

        $this->deleteEmails();

        $this->browse(function (Browser $browser) {
            $browser->visit('/auth/login')
                    ->click('a[href="/auth/password/reset"]')
                    ->type('email', 'admin@gmail.com')
                    ->press('.btn-auth')
                    ->pause(5600)
                    ->assertSee('An email with instructions on how to reset your password should be arriving soon.')
                    ->visit($this->getUrlFromEmail())->assertPresent('.button-primary')
                    ->pause(1500);

                    // Trick to avoid _blank
                    $urlConfirm = $browser->element('.button-primary')->getAttribute('href');
                    $browser->visit($urlConfirm)->screenshot('confirmation_reset_email')->assertSee('RESET YOUR PASSWORD')

                    ->type('password', '11111111')
                    ->type('passwordConfirmation', '11111111')
                    ->press('.btn-auth')
                    ->pause(2400)
                    ->assertSee('Your password has been reset!');
        });
    }
}
