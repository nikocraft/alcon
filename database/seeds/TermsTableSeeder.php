<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Core\Taxonomies\Taxonomy;
use App\Models\Core\Taxonomies\Term;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
            'Categories' => [
                'javascript',
                'php',
                'vuejs'
            ],
            'Tags' => [
                'style',
                'dom',
                'ui'
            ]
        ];

        foreach ($list as $taxonomy_name => $o) {
            $taxonomy = Taxonomy::where('name', $taxonomy_name)->first();
            if ($taxonomy) {
                foreach ($o as $term) {
                    $term = new Term([
                        'name' => $term,
                        'slug' => $term
                    ]);
                    $taxonomy->terms()->save($term);
                }
            }
        }
    }
}
