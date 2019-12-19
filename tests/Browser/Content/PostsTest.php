<?php

namespace Tests\Browser\Content;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostsTest extends DuskTestCase
{

    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_super_user_can_create_empty_post()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('title', $postName)
                    ->press('Save')
                    ->pause(2000);
        });

        $this->get('/posts/' . strtolower($postName))->assertStatus(200);

    }

    public function test_super_user_can_create_post()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $testString = 'Super test headline';
            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('title', $postName)
                    ->pause(2000)
                    ->press('Headline')
                    ->type('#pane-content > div > div.components > div > div > div.content-block-body > h2', $testString)
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/posts/' . $postName)
                    ->assertSee($testString);
        });

    }

    public function test_admin_user_can_create_post()
    {
        $admin = Role::find(2);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($admin);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $testString = 'Admin test headline';
            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('title', $postName)
                    ->pause(2000)
                    ->press('Headline')
                    ->type('#pane-content > div > div.components > div > div > div.content-block-body > h2', $testString)
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/posts/' . $postName)
                    ->assertSee($testString);
        });

    }

    public function test_client_can_create_post()
    {
        $client = Role::find(3);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($client);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $testString = 'Client test headline';
            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('title', $postName)
                    ->pause(2000)
                    ->press('Headline')
                    ->type('#pane-content > div > div.components > div > div > div.content-block-body > h2', $testString)
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/posts/' . $postName)
                    ->assertSee($testString);
        });

    }
}
