<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

class PostsTest extends DuskTestCase
{


    protected static $db_inited = false;

    public function setUp(): void
    {
        parent::setUp();

        if (!static::$db_inited) {
            static::$db_inited = true;
            $path = __DIR__ . '../../../sqlite.testing.database';
            file_put_contents($path, '');
            Artisan::call('laraone:install');
        }
    }

    public function test_super_user_can_create_post()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('title', 'Post Super')
                    ->press('Save')
                    ->pause(3000)
                    ->visit('/posts/post-super')
                    ->pause(3000)
                    ->assertSee('Post Super');
        });

        $this->get('/posts/post-super')->assertStatus(200);
    }

    public function test_admin_user_can_create_post()
    {
        $admin = Role::find(2);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($admin);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('title', 'Post Admin')
                    ->press('Save')
                    ->pause(3000)
                    ->visit('/posts/post-admin')
                    ->pause(3000)
                    ->assertSee('Post Admin');
        });

        $this->get('/posts/post-admin')->assertStatus(200);
    }

    public function test_end_client_can_create_post()
    {
        $endClient = Role::find(3);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($endClient);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('title', 'Post End Client')
                    ->press('Save')
                    ->pause(3000)
                    ->visit('/posts/post-end-client')
                    ->pause(3000)
                    ->assertSee('Post End Client');
        });

        $this->get('/posts/post-end-client')->assertStatus(200);
    }
}
