<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FrontPageTest extends DuskTestCase
{
    use WithFaker;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "FrontPageTest:";
            $this->setupOnce = true;
        }
    }

    public function test_single_page_frontpage()
    {
        echo "test_single_page_frontpage\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $pageName = $this->faker->lexify('??????');
        $testString = 'Hello World, Super User Testing Pages';

        $this->browse(function (Browser $browser) use ($user, $pageName, $testString) {

            // Create a page

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
                    ->pause(2000);

            // Change frontpage setting to single page

            $browser->loginAs($user)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->select('frontPageType', 'single-page')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->assertSelected('frontPageType', 'single-page');

            // Assert frontpage is single page on frontend

            $browser->visit('/')
                    ->assertSee($testString);
        });

    }

    public function test_index_page_frontpage()
    {
        echo "test_index_page_frontpage\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {

            // Create a post

            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('postTitle', $postName)
                    ->pause(2000)
                    ->press('Save')
                    ->pause(2000);

            // Change frontpage setting to index page

            $browser->loginAs($user)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->select('frontPageType', 'index-page')
                    ->select('frontPageMeta', '2')
                    ->press('save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->assertSelected('frontPageType', 'index-page')
                    ->assertSelected('frontPageMeta', '2');

            // Assert frontpage is index page on frontend

            $browser->visit('/')
                    ->assertSee($postName);
        });

    }
}
