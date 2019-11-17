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
        // Pages
        AdminMenu::create([
            'name' => 'Pages',
            'unique_id' =>'core.content.pages',
            'icon' => 'fa fa-file-text',
            'route' => 'content.list',
            'permission' => 'manage-content',
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
            'params' => [
                'contentTypeSlug' => 'posts'
            ]
        ]);

        // Media
        AdminMenu::create([
            'name' => 'Media',
            'unique_id' =>'core.media',
            'icon' => 'fa fa-image',
            'permission' => 'access-media',
            // 'order' => 30,
        ]);

        // Images
        AdminMenu::create([
            'name' => 'Images',
            'unique_id' =>'core.media.images',
            'parent' => AdminMenu::where('unique_id', 'core.media')->value('id'),
            'permission' => 'manage-images',
            'route' => 'media.images',
        ]);

        // USERS
        AdminMenu::create([
            'name' => 'Users & Roles',
            'unique_id' =>'core.acl',
            'icon' => '',
            'permission' => 'access-acl'
        ]);

        AdminMenu::create([
            'name' => 'Users',
            'unique_id' =>'core.users',
            'parent' => AdminMenu::where('unique_id', 'core.acl')->value('id'),
            'permission' => 'manage-users',
            'route' => 'acl.users',
        ]);

        AdminMenu::create([
            'name' => 'Roles',
            'unique_id' =>'core.roles',
            'parent' => AdminMenu::where('unique_id', 'core.acl')->value('id'),
            'permission' => 'manage-roles',
            'route' => 'acl.roles',
        ]);

        // Comments
        AdminMenu::create([
            'name' => 'Comments',
            'unique_id' =>'core.comments',
            'icon' => 'fa fa-comments',
            'permission' => 'list-comments',
            'route' => 'comments',
            // 'order' => 30,
            'visible' => 0
        ]);

        // DESIGN
        AdminMenu::create([
            'name' => 'Design',
            'unique_id' =>'core.design',
            'icon' => '',
            'permission' => 'access-design'
            // 'order' => 40,
        ]);

        AdminMenu::create([
            'name' => 'Customize Theme',
            'unique_id' =>'core.design.customize',
            'parent' => AdminMenu::where('unique_id', 'core.design')->value('id'),
            'route' => 'design.customize',
        ]);

        AdminMenu::create([
            'name' => 'Themes',
            'unique_id' =>'core.design.themes',
            'parent' => AdminMenu::where('unique_id', 'core.design')->value('id'),
            'route' => 'design.themes',
        ]);

        AdminMenu::create([
            'name' => 'Menus',
            'unique_id' =>'core.design.menus',
            'parent' => AdminMenu::where('unique_id', 'core.design')->value('id'),
            'route' => 'menus.list',
        ]);

        AdminMenu::create([
            'name' => 'Widgets',
            'unique_id' =>'core.design.widgets',
            'parent' => AdminMenu::where('unique_id', 'core.design')->value('id'),
            'route' => 'widgets.list',
        ]);

        // Settings
        AdminMenu::create([
            'name' => 'Settings',
            'unique_id' =>'core.settings',
            'icon' => '',
            'permission' => 'access-settings',
            // 'order' => 60,
        ]);

        // Types
        AdminMenu::create([
            'name' => 'Content Types',
            'unique_id' =>'core.settings.types.index',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'permission' => 'all',
            'route' => 'content-types.list',
            'visible' => 0
        ]);

        AdminMenu::create([
            'name' => 'Website',
            'unique_id' =>'core.settings.website',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.website',
        ]);

        AdminMenu::create([
            'name' => 'Admin',
            'unique_id' =>'core.settings.admin',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.backend',
            'visible' => 0
        ]);

        AdminMenu::create([
            'name' => 'Mail',
            'unique_id' =>'core.settings.mail',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.mail',
        ]);

        AdminMenu::create([
            'name' => 'Login',
            'unique_id' =>'core.settings.login',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.login',
        ]);

        AdminMenu::create([
            'name' => 'Members',
            'unique_id' =>'core.settings.members',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.members',
            'visible' => 0
        ]);

        AdminMenu::create([
            'name' => 'Comments',
            'unique_id' =>'core.settings.comments',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.comments',
        ]);

        AdminMenu::create([
            'name' => 'Permalinks',
            'unique_id' =>'core.settings.urls',
            'parent' => AdminMenu::where('unique_id', 'core.settings')->value('id'),
            'route' => 'settings.urls',
        ]);
    }
}
