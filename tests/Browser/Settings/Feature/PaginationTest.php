<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PaginationTest extends DuskTestCase
{

    use WithFaker;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "PaginationTest:";
            $this->setupOnce = true;
        }
    }

    public function test_pagination_on_frontend()
    {
        echo "test_pagination_on_frontend\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        // Create 6 empty posts

        $this->browse(function (Browser $browser) use ($user) {
            $i = 0;
            while($i < 6) {
                $postName = $this->faker->lexify('??????');
                $browser->loginAs($user)
                        ->visit('/admin/content/posts')
                        ->pause(2000)
                        ->assertPathIs('/admin/content/posts')
                        ->press('Create')
                        ->pause(2000)
                        ->assertPathIs('/admin/content/posts/create')
                        ->type('postTitle', $postName)
                        ->press('Save')
                        ->pause(1000);
                $i++;
                }

                // Set pagination per page setting to 5

                $browser->loginAs($user)
                        ->visit('/admin/settings/website')
                        ->pause(2000)
                        ->clear('paginationPerPage')
                        ->type('paginationPerPage', '5')
                        ->press('Save')
                        ->pause(2000)
                        ->visit('/admin/settings/website')
                        ->pause(2000)
                        ->assertInputValue('paginationPerPage', '5');

                // Assert pagination is present on frontend

                $browser->visit('/posts')
                        ->assertPresent('.pagination');
        });

    }
}
