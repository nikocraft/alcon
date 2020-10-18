<?php
namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

use Auth;
use Timezone;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Core\Taxonomies\Term;
use App\Models\Core\Media\Image;
use App\Models\Core\Comments\Comment;
use App\Models\Traits\Sluggable;
use App\Models\Traits\HasBlocks;
use App\Models\Traits\HasSchemalessSettings;

class Content extends Model
{
    use Sluggable, HasBlocks, HasSchemalessSettings;

    protected $table = "content";

    protected $fillable = ['id', 'title', 'slug', 'user_id', 'parent_id', 'status', 'access', 'layout', 'views', 'order', 'sticky', 'js', 'css', 'seo', 'resources', 'settings'];

    // content status
    const PUBLISH = 1;
    const DRAFT = 2;
    const SCHEDULE = 3;

    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
        'published_at'
    ];

    protected $casts = [
        'seo' => 'array',
        'resources' => 'array',
        'settings' => 'array'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function type()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function featuredimage()
    {
        return $this->belongsTo(Image::class, 'featured_image_id');
    }

    public function terms()
    {
        return $this->belongsToMany(Term::class, 'content_term', 'content_id', 'term_id');
    }

    public function scopePosts($query)
    {
        $query->where('content_type_id', 2);
    }

    public function scopePublished($query)
    {
        $query->where('status', self::PUBLISH);
    }

    public function scopeDrafts($query)
    {
        $query->where('status', self::DRAFT);
    }

    public function scopeScheduled($query)
    {
        $query->where('status', self::SCHEDULE);
    }

    public function processBlocks($rawBlocks)
    {
        // sort the blocks
        $rawBlocks = $rawBlocks->sortBy('order');

        // process blocks and return processed array
        $blocks = [];
        foreach ($rawBlocks as $key => $block) {
            if($this->isJson($block['content']))
                $block['content'] = json_decode($block['content'], true);

            array_push($blocks, $block);
        }

        return $blocks;
    }

    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function processTermObject($term = [], $taxonomyId = null)
    {
        $id = null;
        if(!array_key_exists('id', $term)) {
            $newTerm = Term::firstOrCreate([
                'name' => mb_strtolower($term['name']),
                'slug' => $this->slugify($term['name']),
                'taxonomy_id' => $taxonomyId
            ]);
            $id = $newTerm->id;
        } else {
            $id = $term['id'];
        }
        return $id;
    }

    public function setTaxonomies($taxonomiesData = [])
    {
        $termsIds = [];
        foreach($taxonomiesData as $taxonomyKey => $data) {
            if($data) {
                if(!Arr::isAssoc($data)) {
                    foreach($data as $term) {
                        $id = $this->processTermObject($term, $taxonomyKey);
                        array_push($termsIds, $id);
                    }
                } else {
                    $id = $this->processTermObject($data, $taxonomyKey);
                    array_push($termsIds, $id);
                }
            }
        }
        
        $this->terms()->sync($termsIds);
    }
}
