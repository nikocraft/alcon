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

    public function test_super_user_can_create_post()
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

    public function test_admin_user_can_create_post()
    {
        $admin = Role::find(2);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($admin);

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

    public function test_end_client_can_create_post()
    {
        $endClient = Role::find(3);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($endClient);

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
}
