<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Traits\EmailTrait;

class RegisterTest extends DuskTestCase
{

    use WithFaker;
    use EmailTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    // public function test_user_can_register()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/auth/register')
    //                 ->pause(1000)
    //                 ->type('firstname', $this->faker->firstName)
    //                 ->type('lastname', $this->faker->lastName)
    //                 ->type('username', $this->faker->lexify('??????'))
    //                 ->type('email', $this->faker->email)
    //                 ->type('password', '12341234')
    //                 ->press('.btn-auth')
    //                 ->pause(1000)
    //                 ->assertSee('You have successfully registered an account! We\'ve sent you an email to activate your account.');
    //     });
    // }

    // public function test_user_can_register_and_activate_account()
    // {

    //     $this->deleteEmails();

    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/auth/register')
    //                 ->pause(1000)
    //                 ->type('firstname', $this->faker->firstName)
    //                 ->type('lastname', $this->faker->lastName)
    //                 ->type('username', $this->faker->lexify('??????'))
    //                 ->type('email', $this->faker->email)
    //                 ->type('password', '12341234')
    //                 ->press('.btn-auth')
    //                 ->pause(1000)
    //                 ->assertSee('You have successfully registered an account! We\'ve sent you an email to activate your account.')
    //                 ->visit($this->getEmail())->screenshot('email')->assertPresent('.button-primary')
    //                 ->pause(3000);

    //                 // Trick to avoid _blank
    //                 $urlConfirm = $browser->element('.button-primary')->getAttribute('href');
    //                 $browser->visit($urlConfirm)->screenshot('confirmation_email')->assertSee('Your account has now been activated. Click here to Login!');
    //     });
    // }

    public function test_user_cannot_register_if_last_name_field_is_empty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/auth/register')
                    ->pause(3000)
                    ->type('firstname', $this->faker->firstName)
                    ->type('username', $this->faker->lexify('??????'))
                    ->type('email', $this->faker->email)
                    ->type('password', '12341234')
                    ->press('.btn-auth')
                    ->pause(10000)
                    ->assertSee('Required Field');
        });
    }

    // public function test_user_cannot_register_if_username_field_is_empty()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/auth/register')
    //                 ->pause(3000)
    //                 ->type('firstname', $this->faker->firstName)
    //                 ->type('lastname', $this->faker->lastName)
    //                 ->type('email', $this->faker->email)
    //                 ->type('password', '12341234')
    //                 ->press('.btn-auth')
    //                 ->pause(10000)
    //                 ->assertSee('Required Field');
    //     });
    // }

    // public function test_user_cannot_register_if_email_field_is_empty()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/auth/register')
    //                 ->pause(3000)
    //                 ->type('firstname', $this->faker->firstName)
    //                 ->type('lastname', $this->faker->lastName)
    //                 ->type('username', $this->faker->lexify('??????'))
    //                 ->type('password', '12341234')
    //                 ->press('.btn-auth')
    //                 ->pause(10000)
    //                 ->assertSee('Enter Valid Email');
    //     });
    // }

    // public function test_user_cannot_register_if_password_field_is_empty()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/auth/register')
    //                 ->pause(3000)
    //                 ->type('firstname', $this->faker->firstName)
    //                 ->type('lastname', $this->faker->lastName)
    //                 ->type('username', $this->faker->lexify('??????'))
    //                 ->type('email', $this->faker->email)
    //                 ->press('.btn-auth')
    //                 ->pause(10000)
    //                 ->assertSee('Required Field');
    //     });
    // }

    // public function test_logged_in_user_cannot_access_registration_form()
    // {
    //     $super = Role::find(1);
    //     $user = factory(User::class)->create([
    //         'activated' => true
    //     ]);
    //     $user->attachRole($super);

    //     $this->browse(function (Browser $browser) use ($user) {
    //         $browser->loginAs($user)
    //                 ->visit('/auth/register')
    //                 ->pause(3000)
    //                 ->assertPathIs('/admin/content/pages');
    //     });
    // }
}
