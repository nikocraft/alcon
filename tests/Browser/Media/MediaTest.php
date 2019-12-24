<?php

namespace Tests\Browser\Media;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MediaTest extends DuskTestCase
{
    public $setupOnce = false;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->setupOnce) {
            echo "MediaTest:";
            $this->setupOnce = true;
        }
    }

    public function test_user_uploads_and_deletes_image_in_media_library()
    {
        echo "test_user_uploads_and_deletes_image_in_media_library\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/media/images')
                    ->pause(2000)
                    ->assertPathIs('/admin/media/images')
                    ->press('Upload')
                    ->pause(200)
                    ->attach('#file', base_path('public/themes/ikigai/images/screenshot.jpg'))
                    ->pause(3000)
                    ->press('uploadImages')
                    ->pause(5000)
                    ->press('Close')
                    ->pause(3000)
                    ->assertSourceHas('screenshot_medium.jpg')
                    ->press('#app > div > div > section > div > div > div > div.box-body > div.images > div > div > button:nth-child(2)')
                    ->pause(200)
                    ->press('Delete')
                    ->pause(2000)
                    ->assertSourceMissing('screenshot_medium.jpg');
        });
    }

    public function test_user_modifies_image_settings_in_media_library()
    {
        echo "test_user_modifies_image_settings_in_media_library\r\n";
        $super = Role::find(1);
        $user = factory(User::class)->create([
            'activated' => true
        ]);
        $user->attachRole($super);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/media/images')
                    ->pause(2000)
                    ->assertPathIs('/admin/media/images')
                    ->press('Upload')
                    ->pause(200)
                    ->attach('#file', base_path('public/themes/ikigai/images/screenshot.jpg'))
                    ->pause(3000)
                    ->press('uploadImages')
                    ->pause(5000)
                    ->press('Close')
                    ->pause(3000)
                    ->assertSourceHas('screenshot_medium.jpg')
                    ->press('.images > div:nth-child(1) > div > button:nth-child(1)')
                    ->pause(2000)
                    ->type('imageTitle', 'Test title')
                    ->type('.imagetags > div > input', 'test tag')
                    ->keys('.imagetags > div > input', ['{enter}'])
                    ->type('imageAlt', 'Test alt text')
                    ->type('imageCaption', 'Test caption')
                    ->type('imageDescription', 'Test description')
                    ->press('Save')
                    ->pause(2000)
                    ->visit('/admin/media/images')
                    ->pause(2000)
                    ->press('.images > div:nth-child(1) > div > button:nth-child(1)')
                    ->pause(2000)
                    ->assertInputValue('imageTitle', 'Test title')
                    ->assertInputValue('.imagetags > div > span', 'test tag ×')
                    ->assertInputValue('imageAlt', 'Test alt text')
                    ->assertInputValue('imageCaption', 'Test caption')
                    ->assertInputValue('imageDescription', 'Test description');
        });
    }
}
