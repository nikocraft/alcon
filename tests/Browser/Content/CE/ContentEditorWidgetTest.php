<?php

namespace Tests\Browser\Content;

use App\Models\User;
use App\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContentEditorWidgetTest extends DuskTestCase
{
    use WithFaker;

    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "ContentEditorWidgetTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_can_add_exposed_components_in_new_widget() {
        echo "test_user_can_add_exposed_components_in_new_widget\r\n";
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
            $year = date('Y');

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
                    ->press('Save')
                    ->pause(2000)

            // Assert that page renders correctly on frontend

                    ->visit('/')
                    ->assertSee($testHeadline)
                    ->assertSee($testText)
                    ->assertPresent('div.image-block > span > img[src="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(1) > span[href="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(2) > span[href="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.images-block > div > div:nth-child(3) > span[href="/uploads/' . $year . '/' . $month . '/' . $day . '/screenshot.jpg"]')
                    ->assertPresent('div.slider-block > span:nth-child(1) > div > div');
        });
    }
}
