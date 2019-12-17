<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

class PagesTest extends DuskTestCase
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

    public function test_super_user_can_create_page()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('title', 'Homepage Super')
                    ->press('Save')
                    ->pause(3000);
        });

        $this->get('/homepage-super')->assertStatus(200);
    }

    public function test_admin_user_can_create_page()
    {
        $admin = Role::find(2);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($admin);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('title', 'Homepage Admin')
                    ->press('Save')
                    ->pause(3000);
        });

        $this->get('/homepage-admin')->assertStatus(200);
    }

    public function test_end_client_can_create_page()
    {
        $endClient = Role::find(3);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($endClient);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(3000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('title', 'Homepage End Client')
                    ->press('Save')
                    ->pause(3000);
        });

        $this->get('/homepage-end-client')->assertStatus(200);
    }
}
