<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends DuskTestCase
{
    // use RefreshDatabase;
    use DatabaseMigrations {
        runDatabaseMigrations as baseRunDatabaseMigrations;
    }

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->baseRunDatabaseMigrations();
        $this->artisan('db:seed');
    }

    public function testUserCanLogout()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'is_activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/backend/content/pages')
                    ->assertSee('Pages')
                    ->press('@logout-dropdown')
                    ->press('@logout-trigger')
                    ->pause(1000)
                    ->visit('/backend/content/pages')
                    ->assertDontSee('Pages')
                    ->assertGuest();
        });
    }
}
