<?php

namespace Tests\Browser\Content;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PagesTest extends DuskTestCase
{

    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_super_user_can_create_page()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('title', strtolower($postName))
                    ->press('Save')
                    ->pause(1000);
        });

        $this->get('/' . strtolower($postName))->assertStatus(200);
    }

    public function test_admin_user_can_create_page()
    {
        $admin = Role::find(2);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($admin);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('title', strtolower($postName))
                    ->press('Save')
                    ->pause(1000);
        });

        $this->get('/' . strtolower($postName))->assertStatus(200);
    }

    public function test_end_client_can_create_page()
    {
        $endClient = Role::find(3);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($endClient);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('title', strtolower($postName))
                    ->press('Save')
                    ->pause(1000);
        });

        $this->get('/' . strtolower($postName))->assertStatus(200);
    }
}
