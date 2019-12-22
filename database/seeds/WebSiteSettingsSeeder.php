<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class WebSiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $section = Setting::create(['key' => 'cms', 'value' => 'CMS']);
        $section->meta = [
            'installed' => false,
            'phoenix' => '1.0.0',
            'atlas' => '1.0.0'
        ];
        $section->save();

        $section = Setting::create(['key' => 'website', 'value' => 'Website']);
        $section->meta = [
            'general' => [
                'title' => 'Laraone',
                'tagline' => 'Website tagline',
                'url' => '',
                'activeTheme' => 2,
                'frontPageType' => 'welcome-page',
                'frontPageMeta' => 1,
                'paginationType' => 'simple',
                'paginationPerPage' => 12
            ],
            'userLogin' => [
                'theme' => 'light',
                'logoType' => 'text',
                'logoText' => 'LaraOne',
                'logoImage' => '',
                'backgroundImage' => '',
                'termsUrl' => '',
                'privacyPolicyUrl' => '',
                'customCss' => ''
            ],
            'comments' => [
                'type' => 'off',
                'loggedInToComment' => false,
                'allowNested' => true,
                'nestedDepth' => 1,
                'order' => 'asc',
                'moderation' => false,
                'notifyOnComment' => false,
                'notifyOnModeration' => false,
                'disqusChannel' => ''
            ],
            'members' => [
                'allowRegistrations' => false,
                'defaultUserRole' => 'member',
                'userDisplayName' => 'fullname',
                'useRecaptcha' => false,
                'autoApprove' => false,
                'requireFullname' => true,
                'requireStrongPassword' => false,
                'newUserNotification' => false,
                'blacklistUserNameWords' => 'admin, administrator, webmaster, moderator'
            ]
        ];
        $section->save();

        $section = Setting::create(['key' => 'atlas', 'value' => 'Atlas']);
        $section->meta = [
            'general' => [
                'language' => 'en',
                'theme' => 'dark'
            ],
            'content' => [
                'editor' => [
                    'wideLayout' => false,
                    'favoriteBlocks' => 'Headline,Text,Image,Images,Slider,Youtube',
                    'showHeaders' => false,
                    'showLabels' => true,
                    'autoSave' => false,
                    'showTaxonomies' => true,
                    'showFeaturedImage' => true,
                    'showContentDates' => false,
                    'shortcutNotifications' => true,
                    'editorNotifications' => true
                ],
                'indexPage' => [
                    'indexPageDisplay' => 'list',
                    'indexPageGridColumns' => 'column-3',
                    'indexPageGridStyle' => 'inline',
                    'indexPageItemsPerPage' => 12,
                    'indexPageSortBy' => 'latest',
                    'indexPageDisplayAuthor' => true,
                    'indexPageDisplayStatus' => true,
                    'indexPageDisplayCreatedUpdated' => true,
                    'indexPageDisplayFeaturedImage' => true
                ],
            ]
        ];
        $section->save();
    }
}
