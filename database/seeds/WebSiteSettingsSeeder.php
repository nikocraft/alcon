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
        $parent = Website::create(['key' => 'cms', 'value' => 'CMS Info', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'installed', 'value' => 0, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id,  'key' => 'phoenix', 'value' => '1.0.0', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id,  'key' => 'atlas', 'value' => '1.0.0', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id,  'key' => 'locale', 'value' => 'en', 'type' => 'string']);

        $parent = Website::create(['key' => 'website', 'value' => 'Website', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'title', 'value' => 'LaraOne', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'tagline', 'value' => 'Website tagline...', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'url', 'value' => '', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'activeTheme', 'value' => 2, 'type' => 'integer']);

        $parent = Website::create(['key' => 'content', 'value' => 'Content', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'frontPageType', 'value' => 'welcome-page', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'frontPageMeta', 'value' => 1, 'type' => 'integer']);
        Website::create(['parent_id' => $parent->id, 'key' => 'paginationType', 'value' => 'simple', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'paginationPerPage', 'value' => 12, 'type' => 'string']);

        $parent = Website::create(['key' => 'admin', 'value' => 'Admin', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'email', 'value' => '', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'language', 'value' => 'english', 'type' => 'string']);

        $parent = Website::create(['key' => 'comments', 'value' => 'Comments', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'type', 'value' => 'off', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'loggedInToComment', 'value' => false, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'allowNested', 'value' => true, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'nestedDepth', 'value' => 1, 'type' => 'integer']);
        Website::create(['parent_id' => $parent->id, 'key' => 'order', 'value' => 'asc', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'moderation', 'value' => false, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'notifyOnComment', 'value' => false, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'notifyOnModeration', 'value' => false, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'disqusChannel', 'value' => '', 'type' => 'string']);

        $parent = Website::create(['key' => 'adminCustomLogin', 'value' => 'Login Customization', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'theme', 'value' => 'light', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'logoType', 'value' => 'text', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'logoText', 'value' => 'LaraOne', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'logoImage', 'value' => '', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'backgroundImage', 'value' => '', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'termsUrl', 'value' => '', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'privacyPolicyUrl', 'value' => '', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'customCss', 'value' => '', 'type' => 'string']);

        $parent = Website::create(['key' => 'members', 'value' => 'Members', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'allowRegistrations', 'value' => false, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'defaultUserRole', 'value' => 'member', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'userDisplayName', 'value' => 'fullname', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'useRecaptcha', 'value' => false, 'type' =>'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'autoApprove', 'value' => false, 'type' =>'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'requireFullname', 'value' => true, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'requireStrongPassword', 'value' => false, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'newUserNotification', 'value' => false, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'blacklistUserNameWords', 'value' => 'admin, administrator, webmaster, moderator', 'type' => 'string']);

        $parent = Website::create(['key' => 'contentEditor', 'value' => 'Content Editor', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'wideLayout', 'value' => 0, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'favoriteBlocks', 'value' => 'Headline,Text,Image,Images,Slider,Youtube', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'showHeaders', 'value' => 0, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'showLabels', 'value' => 1, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'autoSave', 'value' => 0, 'type' => 'integer']);
        Website::create(['parent_id' => $parent->id, 'key' => 'showTaxonomies', 'value' => 1, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'showFeaturedImage', 'value' => 1, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'showContentDates', 'value' => 0, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'shortcutNotifications', 'value' => 1, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'editorNotifications', 'value' => 1, 'type' => 'boolean']);

        $parent = Website::create(['key' => 'contentIndex', 'value' => 'Content Index', 'type' => 'section']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageDisplay', 'value' => 'list', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageGridColumns', 'value' => 'column-3', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageGridStyle', 'value' => 'inline', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageItemsPerPage', 'value' => 12, 'type' => 'integer']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageSortBy', 'value' => 'latest', 'type' => 'string']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageDisplayAuthor', 'value' => 1, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageDisplayStatus', 'value' => 1, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageDisplayCreatedUpdated', 'value' => 1, 'type' => 'boolean']);
        Website::create(['parent_id' => $parent->id, 'key' => 'indexPageDisplayFeaturedImage', 'value' => 1, 'type' => 'boolean']);
    }
}
