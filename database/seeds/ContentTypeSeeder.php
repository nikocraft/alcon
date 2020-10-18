<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Core\Content\ContentType::create([
            'name' => 'Pages',
            'name_singular' => 'Page',
            'slug' => 'pages',
            'locked' => true,
            'type' => 1
        ]);

        App\Models\Core\Content\ContentType::create([
            'name' => 'Posts',
            'name_singular' => 'Post',
            'slug' => 'posts',
            'front_slug' => 'posts',
            'locked' => true,
            'type' => 2
        ]);

        App\Models\Core\Content\ContentType::create([
            'name' => 'Projects',
            'name_singular' => 'Post',
            'slug' => 'projects',
            'front_slug' => 'projects',
            'locked' => true,
            'type' => 2
        ]);
    }
}
