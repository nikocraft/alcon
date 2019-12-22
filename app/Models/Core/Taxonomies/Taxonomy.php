<?php

namespace App\Models\Core\Taxonomies;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

use App\Models\Core\Content\ContentType;
use App\Models\Traits\Sluggable;
use App\Models\Traits\HasSchemalessAttributes;

class Taxonomy extends Model
{
    use Sluggable;
    use HasSchemalessAttributes;

    protected $fillable = ['content_type_id', 'name', 'name_singular', 'placeholder', 'slug', 'order', 'settings'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'settings' => 'array',
    ];

    public function terms()
    {
        return $this->hasMany(Term::class, 'taxonomy_id', 'id');
    }

    public function getTermsAttribute()
    {
        return $this->terms()->get(['id','name','description','slug','taxonomy_id']);
    }

    public function type()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id');
    }

    static public function createOrUpdate($content_type_id, $taxonomyData, $taxonomySettings)
    {
        $taxonomy = Taxonomy::where('id', isset($taxonomyData['id']) ? $taxonomyData['id'] : 0)
            ->where('content_type_id', $content_type_id)
            ->first();

        if ($taxonomy) {
            $taxonomy->content_type_id = $content_type_id;
            $taxonomy->name = $taxonomyData['name'];
            $taxonomy->name_singular = $taxonomyData['name_singular'];
            $taxonomy->order = $taxonomyData['order'];
            $taxonomy->settings = $taxonomySettings;
            // $taxonomy->update( array_add($taxonomyData, 'content_type_id', $content_type_id) );
            $taxonomy->update();
        } else {
            $taxonomy = new Taxonomy();
            $taxonomy->content_type_id = $content_type_id;
            $taxonomy->name = $taxonomyData['name'];
            $taxonomy->name_singular = $taxonomyData['name_singular'];
            $taxonomy->order = $taxonomyData['order'];
            $taxonomy->settings = $taxonomySettings;
            // $taxonomy->save( array_add($taxonomyData, 'content_type_id', $content_type_id) );
            $taxonomy->save();
        }

        return $taxonomy;
    }

}
