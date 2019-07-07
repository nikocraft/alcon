<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends DuskTestCase
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

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testUserCanLogin()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'is_activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/backend/login')
                    ->clear('email')
                    ->clear('password')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('.btn-login')
                    ->pause(1000)
                    ->assertPathIs('/backend/content/pages')
                    ->assertSee('Pages')
                    ->assertAuthenticatedAs($user)
                    ->logout();
        });
    }
}
