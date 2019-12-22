<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MenuTest extends DuskTestCase
{

    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_create_new_menu()
    {
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $pageName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $pageName) {

            // Create an empty page

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

            // Insert the page in new menu

            $browser->visit('/admin/design/menus/create')
                    ->pause(1200)
                    ->type('title', 'Test Menu')
                    ->select('#app > div > div > section > div > div > div.col-md-3 > div > div.box-body > div:nth-child(1) > select', 'header')
                    ->click('#app > div > div > section > div > div > div.col-md-3 > div > div.box-body > div:nth-child(2) > div > div.el-collapse-item.pages > div:nth-child(1) > div')
                    ->pause(200)
                    ->click('#app > div > div > section > div > div > div.col-md-3 > div > div.box-body > div:nth-child(2) > div > div.el-collapse-item.pages.is-active > div.el-collapse-item__wrap > div > section > div.page')
                    ->pause(200)
                    ->press('Add')
                    ->pause(1000)
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/')
                    ->assertSeeIn('#app > header > nav > div.menu-wrapper > div', $pageName);
        });
    }
}
