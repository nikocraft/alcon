<?php

namespace Tests\Browser\Content;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContentEditorTest extends DuskTestCase
{
    use WithFaker;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "ContentEditorTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_can_add_components_in_new_post() {
        echo "test_user_can_add_components_in_new_post\r\n";
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
                    ->assertPresent('div.image-block > span > img[src="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(1) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(2) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(3) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.slider-block > span:nth-child(1) > div > div')
                    ->assertSourceHas('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/W-fFHeTX70Q" style="border: 0px;">');
        });
    }

    public function test_user_can_add_components_in_new_page() {
        echo "test_user_can_add_components_in_new_page\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $pageName = $this->faker->lexify('??????');

        $this->browse(function (Browser $browser) use ($user, $pageName) {
            $testHeadline = 'Testing headline';
            $testText = 'Testing text component by populating it with random text';
            $day = date("d");
            $month = date('m');

            // Start creating a new page

            $browser->loginAs($user)
                    ->visit('/admin/content/pages')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/pages')
                    ->press('Create')
                    ->pause(2000)
                    ->assertPathIs('/admin/content/pages/create')
                    ->type('postTitle', $pageName)
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

            // Assert that page renders correctly on frontend

                    ->visit('/' . $pageName)
                    ->assertSee($testHeadline)
                    ->assertSee($testText)
                    ->assertPresent('div.image-block > span > img[src="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(1) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(2) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(3) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.slider-block > span:nth-child(1) > div > div')
                    ->assertSourceHas('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/W-fFHeTX70Q" style="border: 0px;">');
        });
    }

    public function test_user_can_add_components_in_new_widget() {
        echo "test_user_can_add_components_in_new_widget\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $testHeadline = 'Testing headline';
            $testText = 'Testing text component by populating it with random text';
            $day = date("d");
            $month = date('m');

            // Create widget and make it visible

            $browser->loginAs($user)
                    ->visit('/admin/design/customize')
                    ->pause(2000)
                    ->assertPathIs('/admin/design/customize')
                    ->click('#tab-content')
                    ->click('#pane-content > div > div:nth-child(2) > div > div.form-group-sub > div:nth-child(1) > div > div.form-group-sub > div > div > div > div > div:nth-child(3)')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/settings/website')
                    ->pause(2000)
                    ->select('frontPageType', 'index-page')
                    ->select('frontPageMeta', '2')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/design/widgets/create')
                    ->pause(2000)
                    ->assertPathIs('/admin/design/widgets/create')
                    ->type('title', 'Test Widget')
                    ->select('#app > div > div > section > div > div > div > div > div.box-body > div:nth-child(2) > select', 'sidebar')

            // Add headline to it

                    ->press('Headline')
                    ->type('div.content-block-body > h2', $testHeadline)

            // Add text component

                    ->press('Text')
                    ->type('div.content-block.content-block_text > div.content-block-body > div > div', $testText)

            // Add image component

                    ->press('Image')
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

                    ->press('Images')
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

                    ->press('Slider')
                    ->pause(400)
                    ->click('div.slider-container > div > div > div > div')
                    ->pause(400)
                    ->click('div.b-masonry-list > div > img')
                    ->press('Insert Image')
                    ->pause(1000)

            // Add youtube component

                    ->press('Youtube')
                    ->pause(200)
                    ->click('div.content-block.content-block_youtube > div.content-block-body > div > div')
                    ->pause(200)
                    ->type('div.modal-body > div > input', 'https://www.youtube.com/watch?v=W-fFHeTX70Q')
                    ->press('Close')
                    ->pause(1600)
                    ->press('Save')
                    ->pause(2600)

            // Assert that page renders correctly on frontend

                    ->visit('/')
                    ->assertSee($testHeadline)
                    ->assertSee($testText)
                    ->assertPresent('div.image-block > span > img[src="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(1) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(2) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(3) > span[href="/uploads/2019/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.slider-block > span:nth-child(1) > div > div')
                    ->assertSourceHas('<iframe width="100%" height="100%" src="https://www.youtube.com/embed/W-fFHeTX70Q" style="border: 0px;">');
        });
    }
}
