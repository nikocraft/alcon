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

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "LoginTest:";
            $this->setupOnce = true;
        }
    }

    public function test_super_user_can_create_empty_page()
    {
        echo "test_super_user_can_create_empty_page\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $pageName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $pageName) {
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('postTitle', strtolower($pageName))
                    ->press('Save')
                    ->pause(2000);
        });

        $this->get('/' . strtolower($pageName))->assertStatus(200);

    }

    public function test_super_user_can_create_page()
    {
        echo "test_super_user_can_create_page\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $pageName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $pageName) {
            $testString = 'Hello World, Super User Testing Pages';
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('postTitle', strtolower($pageName))
                    ->pause(2000)
                    ->press('Headline')
                    ->type('#pane-content > div > div.components > div > div > div.content-block-body > h2', $testString)
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/' . $pageName)
                    ->pause(2000)
                    ->assertSee($testString);
        });

    }

    public function test_admin_user_can_create_page()
    {
        echo "test_admin_user_can_create_page\r\n";
        $admin = Role::find(2);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($admin);

        $pageName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $pageName) {
            $testString = 'Hello World, Admin Testing Pages';
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('postTitle', strtolower($pageName))
                    ->pause(2000)
                    ->press('Headline')
                    ->type('#pane-content > div > div.components > div > div > div.content-block-body > h2', $testString)
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/' . $pageName)
                    ->pause(2000)
                    ->assertSee($testString);
        });

    }

    public function test_client_can_create_page()
    {
        echo "test_client_can_create_page\r\n";
        $client = Role::find(3);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($client);

        $pageName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $pageName) {
            $testString = 'Hello World, Client Testing Pages';
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(1000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('postTitle', strtolower($pageName))
                    ->pause(2000)
                    ->press('Headline')
                    ->type('#pane-content > div > div.components > div > div.content-block.content-block_headline > div.content-block-body > h2', $testString)
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/' . $pageName)
                    ->pause(2000)
                    ->assertSee($testString);
        });

    }
}
