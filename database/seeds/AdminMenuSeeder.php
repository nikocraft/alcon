<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return
     */
    public function run()
    {

        // Dashboard
        AdminMenu::create([
            'name' => 'Dashboard',
            'unique_id' =>'core.dashboard',
            'route' => 'dashboard',
            'order' => 10,
            'visible' => 0,
        ]);

        /** Content Types Start */

        // Pages
        AdminMenu::create([
            'name' => 'Pages',
            'unique_id' =>'core.content.pages',
            'icon' => 'fa fa-file-text',
            'route' => 'content.list',
            'permission' => 'manage-content',
            'order' => 20,
            'params' => [
                'contentTypeSlug' => 'pages'
            ]
        ]);

        // Posts
        AdminMenu::create([
            'name' => 'Posts',
            'unique_id' =>'core.content.posts',
            'icon' => 'lo-icon lo-icon-edit',
            'route' => 'content.list',
            'permission' => 'manage-content',
            'order' => 25,
            'params' => [
                'contentTypeSlug' => 'posts'
            ]
        ]);

        /** Content Types End */

        /** Media Start */
        AdminMenu::create([
            'name' => 'Media',
            'unique_id' =>'core.media',
            'icon' => 'fa fa-image',
            'permission' => 'access-media',
            'order' => 30,
        ]);

        // Images
        AdminMenu::create([
            'name' => 'Images',
            'unique_id' =>'core.media.images',
            'parent' => AdminMenu::where('unique_id', 'core.media')->value('id'),
            'permission' => 'manage-images',
            'route' => 'media.images',
            'order' => 10,
        ]);
        /** Media End */

        /** Comments Start */
        AdminMenu::create([
            'name' => 'Comments',
            'unique_id' =>'core.comments',
            'icon' => 'fa fa-comments',
            'permission' => 'manage-comments',
            'route' => 'comments',
            'order' => 60,
        ]);
        /** Comments End */

        /** Users Start */
        AdminMenu::create([
            'name' => 'Users & Roles',
            'unique_id' =>'core.acl',
            'icon' => '',
            'permission' => 'access-acl',
            'order' => 40,
        ]);

        AdminMenu::create([
            'name' => 'Users',
            'unique_id' =>'core.users',
            'parent' => AdminMenu::where('unique_id', 'core.acl')->value('id'),
            'permission' => 'manage-users',
            'route' => 'acl.users',
            'order' => 10,
        ]);

        AdminMenu::create([
            'name' => 'Roles',
            'unique_id' =>'core.roles',
            'parent' => AdminMenu::where('unique_id', 'core.acl')->value('id'),
            'permission' => 'manage-roles',
            'route' => 'acl.roles',
            'order' => 20,
        ]);
        /** Users End */

        /** Design Start */
        AdminMenu::create([
            'name' => 'Design',
            'unique_id' =>'core.design',
            'icon' => '',
            'permission' => 'access-design',
            'order' => 50,
        ]);

        AdminMenu::create([
            'name' => 'Theme Settings',
            'unique_id' =>'core.design.customize',
            'parent' => AdminMenu::where('unique_id', 'core.design')->value('id'),
            'permission' => 'manage-themes',
            'route' => 'design.customize',
            'order' => 10,
        ]);

        AdminMenu::create([
            'name' => 'Themes',
            'unique_id' =>'core.design.themes',
            'parent' => AdminMenu::where('unique_id', 'core.design')->value('id'),
            'permission' => 'manage-themes',
            'route' => 'design.themes',
            'order' => 20,
        ]);

        AdminMenu::create([
            'name' => 'Menus',
            'unique_id' =>'core.design.menus',
            'parent' => AdminMenu::where('unique_id', 'core.design')->value('id'),
            'permission' => 'manage-menus',
            'route' => 'menus.list',
            'order' => 30,
        ]);

        AdminMenu::create([
            'name' => 'Widgets',
            'unique_id' =>'core.design.widgets',
            'parent' => AdminMenu::where('unique_id', 'core.design')->value('id'),
            'permission' => 'manage-widgets',
            'route' => 'widgets.list',
            'order' => 40,
        ]);
        /** Design End */

        /** Plugins Start */
        AdminMenu::create([
            'name' => 'Plugins',
            'unique_id' =>'core.plugins',
            'permission' => 'access-plugins',
            'visible' => 0,
            'order' => 70,
        ]);
        /** Plugins End */

        /** Settings Start */
        AdminMenu::create([
            'name' => 'Settings',
            'unique_id' =>'core.settings',
            'permission' => 'access-settings',
            'order' => 80,
        ]);

        // Types
        AdminMenu::create([
            'name' => 'Content Types',
            'unique_id' =>'core.settings.types.index',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'permission' => 'all-permissions',
            'route' => 'content-types.list',
            'visible' => 0,
            'order' => 10,
        ]);

        AdminMenu::create([
            'name' => 'Website',
            'unique_id' =>'core.settings.website',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.website',
            'order' => 20,
        ]);

        AdminMenu::create([
            'name' => 'Admin',
            'unique_id' =>'core.settings.admin',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.admin',
            'visible' => 0,
            'order' => 30,
        ]);

        AdminMenu::create([
            'name' => 'Cache',
            'unique_id' =>'core.settings.cache',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.cache',
            'visible' => 0,
            'order' => 40,
        ]);

        AdminMenu::create([
            'name' => 'Security',
            'unique_id' =>'core.settings.security',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.security',
            'visible' => 0,
            'order' => 50,
        ]);

        AdminMenu::create([
            'name' => 'Mail',
            'unique_id' =>'core.settings.mail',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.mail',
            'order' => 60,
        ]);

        AdminMenu::create([
            'name' => 'Auth Page',
            'unique_id' =>'core.settings.authpage',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.auth',
            'order' => 70,
        ]);

        AdminMenu::create([
            'name' => 'Members',
            'unique_id' =>'core.settings.members',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.members',
            'order' => 80,
        ]);

        AdminMenu::create([
            'name' => 'Comments',
            'unique_id' =>'core.settings.comments',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.comments',
            'order' => 90,
        ]);

        AdminMenu::create([
            'name' => 'Permalinks',
            'unique_id' =>'core.settings.urls',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.urls',
            'order' => 100,
            'visible' => 0
        ]);
        /** Settings End */
    }
}
