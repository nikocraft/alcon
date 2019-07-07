<?php

use Illuminate\Database\Seeder;
use App\Models\Core\Content\Content;
use App\Models\Core\Content\ContentBlock;


class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Core\Content\Content::class, 10)->create();

        factory(App\Models\Core\Content\ContentBlock::class, 40)->create();

        factory(App\Models\Core\Comments\Comment::class, 150)->create();
        
    }
}
