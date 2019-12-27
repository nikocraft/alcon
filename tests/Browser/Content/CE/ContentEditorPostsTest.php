<?php

namespace Tests\Browser\Content;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContentEditorPostsTest extends DuskTestCase
{
    use WithFaker;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "ContentEditorPostsTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_can_add_exposed_components_in_new_post() {
        echo "test_user_can_add_exposed_components_in_new_post\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $testHeadline = 'Testing headline';
            $testText = 'Testing text component by populating it with random text';
            $day = date("d");
            $month = date('m');
            $year = date('Y');

            // Start creating a new post

            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('postTitle', $postName)
                    ->pause(2000)

            // Add headline to it

                    ->press('HeadlineButton')
                    ->type('div.content-block-body > h2', $testHeadline)

            // Add text component

                    ->press('TextButton')
                    ->type('div.content-block.content-block_text > div.content-block-body > div > div', $testText)

            // Add image component

                    ->press('ImageButton')
                    ->pause(200)
                    ->click('div.content-block.content-block_image > div.content-block-body > div > div > div > h3')
                    ->pause(800)
                    ->click('#tab-upload')
                    ->pause(200)
                    ->attach('#file', base_path('public/themes/ikigai/images/screenshot.jpg'))
                    ->pause(5000)
                    ->click('div.b-masonry-list > div > img')
                    ->press('Insert Image')
                    ->pause(200)

            // Add images component

                    ->press('ImagesButton')
                    ->click('div.images > div:nth-child(1) > div > div.text-center')
                    ->pause(400)
                    ->click('div.b-masonry-list > div > img')
                    ->press('Insert Image')
                    ->click('div.images > div:nth-child(2) > div > div.text-center')
                    ->pause(400)
                    ->click('div.b-masonry-list > div > img')
                    ->press('Insert Image')
                    ->click('div.images > div:nth-child(3) > div > div.text-center')
                    ->pause(400)
                    ->click('div.b-masonry-list > div > img')
                    ->press('Insert Image')
                    ->pause(400)

            // Add slider component

                    ->press('SliderButton')
                    ->pause(400)
                    ->click('div.slider-container > div > div > div > div')
                    ->pause(400)
                    ->click('div.b-masonry-list > div > img')
                    ->press('Insert Image')
                    ->pause(1000)

            // Add youtube component

                    ->press('YoutubeButton')
                    ->pause(200)
                    ->click('div.content-block.content-block_youtube > div.content-block-body > div > div')
                    ->pause(200)
                    ->type('div.modal-body > div > input', 'https://www.youtube.com/watch?v=W-fFHeTX70Q')
                    ->press('Close')
                    ->pause(600)
                    ->press('Save')
                    ->pause(2600)

            // Assert that post renders correctly on frontend

                    ->visit('/posts/' . $postName)
                    ->assertSee($testHeadline)
                    ->assertSee($testText)
                    ->assertPresent('div.image-block > span > img[src="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(1) > span[href="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(2) > span[href="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(3) > span[href="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.slider-block > span:nth-child(1) > div > div')
                    ->assertSourceHas('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/W-fFHeTX70Q" style="border: 0px;">');
        });
    }

    public function test_user_can_add_plus_components_in_new_post() {
        echo "test_user_can_add_plus_components_in_new_post\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $postName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $postName) {
            $testHeadline = 'Testing headline';
            $testText = 'Testing text component by populating it with random text';
            $day = date("d");
            $month = date('m');
            $year = date('Y');

            // Create 4 empty posts

            $i = 0;
            while($i < 4) {
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
                $postName = $this->faker->lexify('??????');
                $i++;
            }

            // Start creating a new post

            $browser->loginAs($user)
                    ->visit('/admin/content/posts')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts')
                    ->press('Create')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/posts/create')
                    ->type('postTitle', $postName)
                    ->pause(2000)

            // Add container component and recent posts inside it

                    ->press('addComponentButton')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(1)')
                    ->press('Insert')
                    ->mouseover('#pane-content > div > div.components > div > div.content-block.content-block_container > div.content-block-body > div > div')
                    ->pause(400)
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_container > div.content-block-header.-label > div.pull-right.content-block-tools-right > i.lo-icon.lo-icon-plus')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(2)')
                    ->press('Insert')

            // Add section component and headline inside it

                    ->press('addComponentButton')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(2)')
                    ->press('Insert')
                    ->mouseover('#pane-content > div > div.components > div > div.content-block.content-block_section > div.content-block-body > div > div')
                    ->pause(400)
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_section > div.content-block-header.-label > div.pull-right.content-block-tools-right > i.lo-icon.lo-icon-plus')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(8)')
                    ->press('Insert')
                    ->pause(200)
                    ->type('#pane-content > div > div.components > div > div.content-block.content-block_section > div.content-block-body > div > div > div > div.content-block-body > h2', 'Testing section headline')
                    ->pause(200)

            // Add tabs component and headline/text inside it

                    ->press('addComponentButton')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(3)')
                    ->press('Insert')
                    ->mouseover('#tab-1 > div.content-block-header.-label')
                    ->pause(400)
                    ->click('#tab-1 > div.content-block-header.-label > div.pull-right.content-block-tools-right > i.lo-icon.lo-icon-plus')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(5)')
                    ->pause(400)
                    ->press('Insert')
                    ->pause(200)
                    ->type('#tab-1 > div.content-block-body > div > div > div > div.content-block-body > h2', 'Testing headline')
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_tabs > div.content-block-body > div > div.tabsbar > div:nth-child(2)')
                    ->mouseover('#tab-2 > div.content-block-header.-label')
                    ->click('#tab-2 > div.content-block-header.-label > div.pull-right.content-block-tools-right > i.lo-icon.lo-icon-plus')
                    ->pause(600)
                    ->click('#pane-components > section > div:nth-child(3)')
                    ->pause(600)
                    ->press('Insert')
                    ->pause(600)
                    ->type('#tab-2 > div.content-block-body > div > div > div > div.content-block-body > div > div', 'Testing text')

            // Add columns component and image/images inside it

                    ->press('addComponentButton')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(4)')
                    ->press('Insert')
                    ->mouseover('#pane-content > div > div.components > div > div.content-block.content-block_columns > div.content-block-body > div > div:nth-child(1) > div.content-block-header.-label')
                    ->pause(400)
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_columns > div.content-block-body > div > div:nth-child(1) > div.content-block-header.-label > div.pull-right.content-block-tools-right > i.lo-icon.lo-icon-plus')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(7)')
                    ->pause(400)
                    ->press('Insert')
                    ->pause(400)
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_columns > div.content-block-body > div > div:nth-child(1) > div.content-block-body > div > div > div > div.content-block-body > div > div > div > h3')
                    ->pause(400)
                    ->click('#tab-upload')
                    ->pause(200)
                    ->attach('#file', base_path('public/themes/ikigai/images/screenshot.jpg'))
                    ->pause(5000)
                    ->click('div.b-masonry-list > div > img')
                    ->press('Insert Image')
                    ->pause(200)
                    ->mouseover('#pane-content > div > div.components > div > div.content-block.content-block_columns > div.content-block-body > div > div:nth-child(2) > div.content-block-header.-label')
                    ->pause(400)
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_columns > div.content-block-body > div > div:nth-child(2) > div.content-block-header.-label > div.pull-right.content-block-tools-right > i.lo-icon.lo-icon-plus')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(8)')
                    ->press('Insert')
                    ->pause(400)
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_columns > div.content-block-body > div > div:nth-child(2) > div.content-block-body > div > div > div > div.content-block-body > div > div.images > div:nth-child(1) > div > div.text-center')
                    ->pause(200)
                    ->click('#pane-library > div.el-tab-pane_items-container > section > div.b-masonry-list > div > img')
                    ->press('Insert')
                    ->pause(400)
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_columns > div.content-block-body > div > div:nth-child(2) > div.content-block-body > div > div > div > div.content-block-body > div > div.images > div:nth-child(2) > div > div.text-center')
                    ->pause(200)
                    ->click('#pane-library > div.el-tab-pane_items-container > section > div.b-masonry-list > div > img')
                    ->press('Insert')
                    ->pause(400)
                    ->click('#pane-content > div > div.components > div > div.content-block.content-block_columns > div.content-block-body > div > div:nth-child(2) > div.content-block-body > div > div > div > div.content-block-body > div > div.images > div:nth-child(3) > div > div.text-center')
                    ->pause(200)
                    ->click('#pane-library > div.el-tab-pane_items-container > section > div.b-masonry-list > div > img')
                    ->press('Insert')

            // Add excerpt component

                    ->press('addComponentButton')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(6)')
                    ->press('Insert')
                    ->pause(200)
                    ->type('#pane-content > div > div.components > div > div.content-block.content-block_excerpt > div.content-block-body > div > textarea', 'Testing excerpt component...')

            // Add button component

                    ->press('addComponentButton')
                    ->pause(400)
                    ->click('#pane-components > section > div:nth-child(11)')
                    ->press('Insert')
                    ->pause(200)
                    ->type('#pane-content > div > div.components > div > div.content-block.content-block_button > div.content-block-body > div > button > h3', 'Test Button')
                    ->press('Save')
                    ->pause(2000)

            // Assert that post renders correctly on frontend

                    ->visit('/posts')
                    ->pause(1600)
                    ->assertSeeIn('#app > div > div.content.content-index.content-left > div > div:nth-child(1) > div.post-excerpt', 'Testing excerpt component...')
                    ->visit('/posts/' . $postName)
                    ->assertSee($postName)
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.container > div > div')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.section')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.section > h2')
                    ->assertSee('Testing section headline')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.tabs-block')
                    ->click('#tab-nav-tab-1')
                    ->pause(200)
                    ->assertPresent('#tab-1 > h2')
                    ->assertSee('Testing headline')
                    ->click('#tab-nav-tab-2')
                    ->pause(200)
                    ->assertPresent('#tab-2 > div > p')
                    ->assertSee('Testing text')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.columns-block')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.columns-block > div.column-block > div > span > img[src="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.columns-block > div.column-block > div > div > div:nth-child(1) > span > img[src="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot_large.jpg"]')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.columns-block > div.column-block > div > div > div:nth-child(2) > span > img[src="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot_large.jpg"]')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.columns-block > div.column-block > div > div > div:nth-child(3) > span > img[src="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot_large.jpg"]')
                    ->assertPresent('#app > div > div.content.content-single.content-left > div > div.btn.button-block.h3 > a')
                    ->assertSee('Test Button');
        });
    }
}
