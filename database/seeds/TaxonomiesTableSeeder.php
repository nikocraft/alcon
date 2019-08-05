<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TaxonomiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoriesSettings = [
            'allowCreate'=> true,
            'allowFilterable' => true,
            'allowMultiple' => true,
            'canHaveChildren' => false,
            'maxAllowed' => 2,
            'required' => false
        ];

        $tagsSettings = [
            'allowCreate' => true,
            'allowFilterable' => true,
            'allowMultiple' => true,
            'canHaveChildren' => false,
            'maxAllowed' => 4,
            'required' => false
        ];

        $categoriesTaxonomy = App\Models\Core\Taxonomies\Taxonomy::create([
            'name' => 'Categories',
            'name_singular' => 'Category',
            'slug' => 'categories',
            'content_type_id' => 2,
            'settings' => $categoriesSettings
        ]);

        $tagsTaxonomy = App\Models\Core\Taxonomies\Taxonomy::create([
            'name' => 'Tags',
            'name_singular' => 'Tag',
            'slug' => 'tags',
            'content_type_id' => 2,
            'settings' => $tagsSettings
        ]);

    }
}
