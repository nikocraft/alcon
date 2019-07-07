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
            'allowCreate' => [
                'type' => 'boolean',
                'value' => true
            ],
            'allowFilterable' => [
                'type' => 'boolean',
                'value' => true
            ],
            'allowMultiple' => [
                'type' => 'boolean',
                'value' => true
            ],
            'canHaveChildren' => [
                'type' => 'boolean',
                'value' => false
            ],
            'maxAllowed' => [
                'type' => 'number',
                'value' => 2
            ],
            'required' => [
                'type' => 'boolean',
                'value' => false
            ],
            'visibleOnIndexPage' => [
                'type' => 'boolean',
                'value' => false
            ]
        ];

        $tagsSettings = [
            'allowCreate' => [
                'type' => 'boolean',
                'value' => true
            ],
            'allowFilterable' => [
                'type' => 'boolean',
                'value' => true
            ],
            'allowMultiple' => [
                'type' => 'boolean',
                'value' => true
            ],
            'canHaveChildren' => [
                'type' => 'boolean',
                'value' => false
            ],
            'maxAllowed' => [
                'type' => 'number',
                'value' => 12
            ],
            'required' => [
                'type' => 'boolean',
                'value' => false
            ],
            'visibleOnIndexPage' => [
                'type' => 'boolean',
                'value' => false
            ]
        ];

        $categoriesTaxonomy = App\Models\Core\Taxonomies\Taxonomy::create([
            'name' => 'Categories',
            'name_singular' => 'Category',
            'slug' => 'categories',
            'content_type_id' => 2,
        ]);

        $tagsTaxonomy = App\Models\Core\Taxonomies\Taxonomy::create([
            'name' => 'Tags',
            'name_singular' => 'Tag',
            'slug' => 'tags',
            'content_type_id' => 2,
        ]);

        foreach ($categoriesSettings as $key => $setting) {
            $categoriesTaxonomy->setSetting($key, $setting['value'], $setting['type']);
        }

        foreach ($tagsSettings as $key => $setting) {
            $tagsTaxonomy->setSetting($key, $setting['value'], $setting['type']);
        }
    }
}
