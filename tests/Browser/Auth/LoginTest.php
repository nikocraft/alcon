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

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_login_and_logout()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/auth/login')
                    ->pause(200)
                    ->assertPathIs('/admin/content/pages')
                    ->assertSee('Pages')
                    ->assertAuthenticatedAs($user)
                    ->logout()
                    ->pause(200)
                    ->visit('/admin/content/pages')
                    ->pause(200)
                    ->assertPathIs('/auth/login');
        });
    }

    public function test_guest_cannot_view_admin_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/content/pages')
                    ->pause(200)
                    ->assertPathIs('/auth/login');
        });
    }

    public function test_user_enters_wrong_password_and_fails_logging_in() {
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
        $this->browse(function (Browser $browser) {
            $browser->visit('/auth/login')
                    ->click('a[href="/auth/password/reset"]')
                    ->type('email', 'example@example.com')
                    ->press('.btn-auth')
                    ->pause(200)
                    ->assertSee("We can't find a user with that e-mail address.");
        });
    }

    public function test_user_successfully_resets_password() {

        $this->deleteEmails();

        $this->browse(function (Browser $browser) {
            $browser->visit('/auth/login')
                    ->click('a[href="/auth/password/reset"]')
                    ->type('email', 'admin@gmail.com')
                    ->press('.btn-auth')
                    ->pause(2500)
                    ->assertSee('An email with instructions on how to reset your password should be arriving soon. If you do not recieve the email, get in touch with us so we can help.')
                    ->visit($this->getUrlFromEmail())->assertPresent('.button-primary')
                    ->pause(1500);

                    // Trick to avoid _blank
                    $urlConfirm = $browser->element('.button-primary')->getAttribute('href');
                    $browser->visit($urlConfirm)->screenshot('confirmation_reset_email')->assertSee('RESET YOUR PASSWORD')

                    ->type('password', '11111111')
                    ->type('passwordConfirmation', '11111111')
                    ->press('.btn-auth')
                    ->pause(1500)
                    ->assertSee('Your password has been reset!');
        });
    }
}
