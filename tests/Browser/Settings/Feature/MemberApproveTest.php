<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Traits\EmailTrait;

class MemberApproveTest extends DuskTestCase
{
    use WithFaker;
    use Emailtrait;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "MemberApproveTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_can_register_and_be_autoapproved()
    {
        echo "test_user_can_register_and_be_autoapproved\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/settings/members')
                    ->pause(2000)
                    ->select('allowRegistrations', 'true')
                    ->pause(1000)
                    ->select('autoApprove', 'true')
                    ->press('save')
                    ->pause(2000)
                    ->assertSelected('allowRegistrations', 'true')
                    ->assertSelected('autoApprove', 'true')
                    ->logout();

                    $this->deleteEmails();

            $browser->visit('/auth/register')
                    ->pause(1000)
                    ->type('firstname', $this->faker->lexify('??????'))
                    ->type('lastname', $this->faker->lexify('??????'))
                    ->type('username', $this->faker->lexify('??????'))
                    ->type('email', $this->faker->email)
                    ->type('password', '12341234')
                    ->press('.btn-auth')
                    ->pause(5400)
                    ->assertSee('You have successfully registered an account! We\'ve sent you an email to activate your account.')
                    ->visit($this->getUrlFromEmail())->assertPresent('.button-primary')
                    ->pause(1000);

            // Trick to avoid _blank
            $urlConfirm = $browser->element('.button-primary')->getAttribute('href');
            $browser->visit($urlConfirm)->screenshot('confirmation_email')->assertSee('Your account has now been activated. Click here to Login!');
        });

    }

}
