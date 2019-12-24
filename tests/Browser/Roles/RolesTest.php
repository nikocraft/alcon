<?php

namespace Tests\Browser\Roles;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RolesTest extends DuskTestCase
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

    public function test_user_can_create_read_update_delete_role()
    {
        echo "test_user_can_create_read_update_delete_role\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/acl/roles')
                    ->pause(3000)
                    ->assertPathIs('/admin/acl/roles')
                    ->press('Create')
                    ->pause(600)
                    ->type('name', 'test')
                    ->type('display_name', 'Test')
                    ->click('section > div.permissions > div:nth-child(2)')
                    ->click('section > div.permissions > div:nth-child(4)')
                    ->click('section > div.permissions > div:nth-child(6)')
                    ->click('section > div.permissions > div:nth-child(8)')
                    ->click('section > div.permissions > div:nth-child(10)')
                    ->press('div.modal-footer > button')
                    ->pause(5000)
                    ->assertSeeIn('table.table-content', 'Test')
                    ->press('table > tbody > tr:last-child > td:last-child > button.btn.btn-primary.edit-btn')
                    ->pause(600)
                    ->type('display_name', 'Edited')
                    ->press('div.modal-footer > button')
                    ->pause(5000)
                    ->assertSeeIn('table.table-content', 'Edited')
                    ->press('table > tbody > tr:last-child > td:last-child > button.btn.btn-xs.btn-danger.delete-btn')
                    ->pause(500)
                    ->press('Delete')
                    ->pause(5000)
                    ->assertDontSeeIn('table.table-content', 'Edited');
        });
    }

}
