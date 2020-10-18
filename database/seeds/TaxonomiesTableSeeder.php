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
            'maxAllowed' => 4,
            'required' => false
        ];

        $tagsSettings = [
            'allowCreate' => true,
            'allowFilterable' => true,
            'allowMultiple' => true,
            'canHaveChildren' => false,
            'maxAllowed' => 8,
            'required' => false
        ];

        $projectTypesSettings = [
            'allowCreate' => true,
            'allowFilterable' => true,
            'allowMultiple' => true,
            'canHaveChildren' => false,
            'maxAllowed' => 3,
            'required' => false
        ];

        App\Models\Core\Taxonomies\Taxonomy::create([
            'name' => 'Categories',
            'name_singular' => 'Category',
            'slug' => 'categories',
            'content_type_id' => 2,
            'settings' => $categoriesSettings
        ]);

        App\Models\Core\Taxonomies\Taxonomy::create([
            'name' => 'Tags',
            'name_singular' => 'Tag',
            'slug' => 'tags',
            'content_type_id' => 2,
            'settings' => $tagsSettings
        ]);

        App\Models\Core\Taxonomies\Taxonomy::create([
            'name' => 'Types',
            'name_singular' => 'Type',
            'slug' => 'types',
            'content_type_id' => 3,
            'settings' => $projectTypesSettings
        ]);

    }
}
