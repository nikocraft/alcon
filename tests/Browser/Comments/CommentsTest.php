<?php

namespace Tests\Browser\Comments;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CommentsTest extends DuskTestCase
{

    use WithFaker;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "CommentsTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_posts_a_native_comment()
    {
        echo "test_user_posts_a_native_comment\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $postName = $this->faker->lexify('??????');

            // Create new post

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

            // Turn on comments

            $browser->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'native')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertSelected('type', 'native');

            // Go to new post and write a test comment

            $browser->visit('/posts/' . $postName)
                    ->pause(1000)
                    ->assertSee($postName)
                    ->click('div.load-comments-wrapper > div.btn-primary')
                    ->pause(2000)
                    ->type('body', 'A test comment goes here.')
                    ->press('Comment')
                    ->pause(3000)
                    ->visit('/posts/' . $postName)
                    ->pause(1000)
                    ->click('div.load-comments-wrapper > div.btn-primary')
                    ->pause(2000)
                    ->assertSee('A test comment goes here.');

            // Go to comments section and test comment status

            $browser->visit('/admin/comments?status=all')
                    ->pause(3000)
                    ->assertSee($postName)
                    ->click('span.unapprove')
                    ->pause(3000)
                    ->click('div.pull-left > a:nth-child(3)')
                    ->pause(3000)
                    ->assertSee($postName)
                    ->click('span.approve')
                    ->pause(3000)
                    ->click('div.pull-left > a:nth-child(2)')
                    ->pause(3000)
                    ->assertSee($postName)
                    ->click('span.spam')
                    ->pause(3000)
                    ->click('div.pull-left > a:nth-child(4)')
                    ->pause(3000)
                    ->assertSee($postName)
                    ->click('span.delete')
                    ->pause(200)
                    ->press('Delete')
                    ->pause(3000)
                    ->assertDontSee($postName)
                    ->assertSee('No comments.');
        });

    }

    public function test_user_turns_off_comments()
    {
        echo "test_user_turns_off_comments\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
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

            $browser->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertPathIs('/admin/settings/comments')
                    ->select('type', 'off')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/comments')
                    ->pause(2000)
                    ->assertSelected('type', 'off');

            $browser->visit('/posts/' . $postName)
                    ->pause(1000)
                    ->assertSee($postName)
                    ->assertDontSee('Load Comments');
        });

    }
}
