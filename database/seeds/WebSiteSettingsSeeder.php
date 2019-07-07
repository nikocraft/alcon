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
        $parent = Website::createSetting(null, 'website', 'General', 'section', null);
        Website::createSetting($parent->id, 'title', 'LaraOne', 'string', null);
        Website::createSetting($parent->id, 'tagline', 'Best in the world...', 'string', null);
        Website::createSetting($parent->id, 'url', '', 'string', null);
        Website::createSetting($parent->id, 'frontPageType', 'welcome-page', 'string', null);
        Website::createSetting($parent->id, 'frontPageMeta', 1, 'integer', null);
        Website::createSetting($parent->id, 'activeTheme', 1, 'integer', null);
        Website::createSetting($parent->id, 'installed', 0, 'boolean', null);

        $parent = Website::createSetting(null, 'admin', 'Admin', 'section', null);
        Website::createSetting($parent->id, 'email', '', 'string', null);
        Website::createSetting($parent->id, 'language', 'english', 'string', null);

        $parent = Website::createSetting(null, 'comments', 'Comments', 'section', null);
        Website::createSetting($parent->id, 'type', 'native', 'string', null);
        Website::createSetting($parent->id, 'loggedInToComment', false, 'boolean', null);
        Website::createSetting($parent->id, 'allowNested', true, 'boolean', null);
        Website::createSetting($parent->id, 'nestedDepth', 1, 'integer', null);
        Website::createSetting($parent->id, 'order', 'asc', 'string', null);
        Website::createSetting($parent->id, 'moderation', false, 'boolean', null);
        Website::createSetting($parent->id, 'notifyOnComment', false, 'boolean', null);
        Website::createSetting($parent->id, 'notifyOnModeration', false, 'boolean', null);
        Website::createSetting($parent->id, 'disqusChannel', '', 'string', null);

        $parent = Website::createSetting(null, 'adminLogin', 'Admin Login', 'section', null);
        Website::createSetting($parent->id, 'logoType', 'text', 'string', null);
        Website::createSetting($parent->id, 'logoText', 'LaraOne', 'string', null);
        Website::createSetting($parent->id, 'logoTextColor', 'black', 'string', null);
        Website::createSetting($parent->id, 'logoImage', '', 'string', null);
        Website::createSetting($parent->id, 'loginBoxMargin', '0px', 'string', null);
        Website::createSetting($parent->id, 'backgroundColor', '#4C8DAA', 'string', null);
        Website::createSetting($parent->id, 'backgroundImage', '', 'string', null);
        Website::createSetting($parent->id, 'loginBoxPosition', 'center', 'string', null);
        Website::createSetting($parent->id, 'loginBoxFullHeight', '0', 'boolean', null);
        Website::createSetting($parent->id, 'loginBoxBackgroundColor', 'white', 'string', null);
        Website::createSetting($parent->id, 'logoTextBackgroundColor', 'white', 'string', null);

        $parent = Website::createSetting(null, 'members', 'Members', 'section', null);
        Website::createSetting($parent->id, 'whoCanRegister', 'everyone', 'string', null);
        Website::createSetting($parent->id, 'defaultUserRole', 'member', 'string', null);
        Website::createSetting($parent->id, 'userDisplayName', 'username', 'string', null);
        Website::createSetting($parent->id, 'useRecaptcha', true, 'boolean', null);
        Website::createSetting($parent->id, 'registrationStatus', 'admin', 'string', null);
        Website::createSetting($parent->id, 'requireFirstLastName', true, 'boolean', null);
        Website::createSetting($parent->id, 'requireStrongPassword', true, 'boolean', null);
        Website::createSetting($parent->id, 'newUserNotification', true, 'boolean', null);
        Website::createSetting($parent->id, 'blacklistUserNameWords', 'admin, administrator, webmaster, moderator', 'string', null);

        $parent = Website::createSetting(null, 'contentEditor', 'Content Editor', 'section', null);
        Website::createSetting($parent->id, 'wideLayout', 0, 'boolean', null);
        Website::createSetting($parent->id, 'favoriteBlocks', 'Headline,Text,Image,Images,Slider,Youtube', 'string', null);
        Website::createSetting($parent->id, 'showHeaders', 0, 'boolean', null);
        Website::createSetting($parent->id, 'showLabels', 1, 'boolean', null);
        Website::createSetting($parent->id, 'autoSave', 0, 'integer', null);
        Website::createSetting($parent->id, 'showTaxonomies', 1, 'boolean', null);
        Website::createSetting($parent->id, 'showFeaturedImage', 1, 'boolean', null);
        Website::createSetting($parent->id, 'showContentDates', 0, 'boolean', null);
        Website::createSetting($parent->id, 'shortcutNotifications', 1, 'boolean', null);
        Website::createSetting($parent->id, 'editorNotifications', 1, 'boolean', null);

        $parent = Website::createSetting(null, 'contentIndex', 'Content Index', 'section', null);
        Website::createSetting($parent->id, 'indexPageDisplay', 'list', 'string', null);
        Website::createSetting($parent->id, 'indexPageGridColumns', 'column-3', 'string', null);
        Website::createSetting($parent->id, 'indexPageGridStyle', 'inline', 'string', null);
        Website::createSetting($parent->id, 'indexPageItemsPerPage', 12, 'integer', null);
        Website::createSetting($parent->id, 'indexPageSortBy', 'latest', 'string', null);
        Website::createSetting($parent->id, 'indexPageDisplayAuthor', 1, 'boolean', null);
        Website::createSetting($parent->id, 'indexPageDisplayStatus', 1, 'boolean', null);
        Website::createSetting($parent->id, 'indexPageDisplayCreatedUpdated', 1, 'boolean', null);
        Website::createSetting($parent->id, 'indexPageDisplayFeaturedImage', 1, 'boolean', null);
    }
}
