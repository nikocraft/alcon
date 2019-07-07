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
            'front_slug' => 'pages',
            'locked' => true
        ]);

        App\Models\Core\Content\ContentType::create([
            'name' => 'Posts',
            'name_singular' => 'Post',
            'slug' => 'posts',
            'front_slug' => 'posts',
            'locked' => true,
            'type' => 2
        ]);
    }
}
