<?php

namespace Tests\Browser\Users;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersTest extends DuskTestCase
{

    use WithFaker;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "UsersTest:";
            $this->setupOnce = true;
        }
    }

    public function test_admin_can_create_and_delete_new_users()
    {
        echo "test_admin_can_create_and_delete_new_users\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $firstname = $this->faker->lexify('??????');
            $username = $this->faker->lexify('??????');
            $email = $this->faker->email;
            $browser->loginAs($user)
                    ->visit('/admin/acl/users')
                    ->pause(2000)
                    ->assertPathIs('/admin/acl/users')
                    ->press('Create')
                    ->pause(1400)
                    ->type('firstname', $firstname)
                    ->type('lastname', $this->faker->lexify('??????'))
                    ->type('username', $username)
                    ->type('email', $email)
                    ->type('password', $this->faker->lexify('????????'))
                    ->type('bio', $this->faker->lexify('??????'))
                    ->check('#app > div > div > section > div > div > div.v--modal-overlay > div.v--modal-box.v--modal.modal-content > section > div:nth-child(5) > div > div > label:nth-child(2)')
                    ->check('#app > div > div > section > div > div > div.v--modal-overlay > div.v--modal-box.v--modal.modal-content > section > div:nth-child(7) > div > div > label:nth-child(1)')
                    ->pause(800)
                    ->press('#app > div > div > section > div > div > div.v--modal-overlay > div.v--modal-box.v--modal.modal-content > div.modal-footer > button.btn.btn-primary.pull-right')
                    ->pause(4000)
                    ->visit('/admin/acl/users')
                    ->pause(2000)
                    ->assertSee($firstname)->assertSee($username)->assertSee($email)
                    ->press('#app > div > div > section > div > div > div > div.box-body > table > tbody > tr:nth-child(5) > td:nth-child(9) > button.btn.btn-xs.btn-danger.delete-btn')
                    ->press('Delete')
                    ->visit('/admin/acl/users')
                    ->pause(2000)
                    ->assertDontSee($firstname)->assertDontSee($username)->assertDontSee($email);

        });
    }

    public function test_admin_can_update_other_users()
    {
        echo "test_admin_can_update_other_users\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $firstname = $this->faker->lexify('??????');
            $username = $this->faker->lexify('??????');
            $email = $this->faker->email;
            $browser->loginAs($user)
                    ->visit('/admin/acl/users')
                    ->pause(2000)
                    ->assertPathIs('/admin/acl/users')
                    ->press('#app > div > div > section > div > div > div > div.box-body > table > tbody > tr:nth-child(2) > td:nth-child(9) > button.btn.btn-primary.edit-btn')
                    ->pause(1600)
                    ->type('firstname', $firstname)
                    ->type('lastname', $this->faker->lexify('??????'))
                    ->type('username', $username)
                    ->type('email', $email)
                    ->pause(200)
                    ->press('Update')
                    ->pause(4000)
                    ->visit('/admin/acl/users')
                    ->pause(2000)
                    ->assertSee($firstname)->assertSee($username)->assertSee($email);

        });
    }
}
