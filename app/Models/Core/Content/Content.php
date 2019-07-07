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
use App\Models\Core\Settings\Website;

use App\Models\Traits\Sluggable;
// use App\Models\Core\Settings\HasSettings;

class Content extends Model
{
    use Sluggable;
    // use HasSettings;

    protected $table = "content";

    protected $fillable = ['id'];

    // content status
    const PUBLISH = 1;
    const DRAFT = 2;
    const SCHEDULE = 3;

    // content access
    const EVERYONE = 1; // accessable by public
    const MEMBERS = 2; // anyone that is a member on the site
    const PREMIUM_MEMBERS = 3; // accessable only be premium members, can be different types of premium members

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

    public function blocks()
    {
        return $this->hasMany(ContentBlock::class, 'content_id', 'id');
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
        foreach ($rawBlocks as $key => $block) {
            $title = $block->settings;
            foreach ($block->settings as $key => $setting) {
                if($setting['key'] == 'order') {
                    $block->order = (int)$setting['value'];
                    break;
                }
            }
        }

        // sort the blocks
        $rawBlocks = $rawBlocks->sortBy('order');

        // reset keys
        $blocks = [];
        foreach ($rawBlocks as $key => $block) {
            if($this->isJson($block['content']))
                $block['content'] = json_decode($block['content'], true);

            array_push($blocks, $block);
        }

        return $blocks;
    }

    public function getSettingsAsJsonAttribute($value)
    {
        return json_decode($this->attributes['settings']);
    }

    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function comment($request)
    {
        if(get_website_setting('comments.commentModeration')) {
            $status = Comment::PENDING;
            // if(!Setting::get('Comments', 'moderationForApprovedUsers')) {
            //     if(Comment::where('email', $request->email)->where('status', Comment::APPROVED)->exists())
            //         $status = Comment::APPROVED;
            // }
        }
        else
            $status = Comment::APPROVED;

        if(Auth::user()) {
            $user_id = Auth::user()->id;
            $status = Comment::APPROVED;
            $email = Auth::user()->email;
            $name = Auth::user()->firstname .' '. Auth::user()->lastname;
            $website = $request->website;
        } else {
            $user_id = null;
            $email = $request->email;
            $name = $request->name;
            $website = $request->website;
        }

        $contentId = $request->content_id;
        $body = $request->body;
        $parent = $request->parent;
        $visitorIp = $request->ip;
        // $status = Comment::APPROVED;

        $comment = $this->comments()->create([
            'user_id' => $user_id,
            'content_id' => $contentId,
            'body' => $body,
            'parent' => $parent,
            'name' => $name,
            'email' => $email,
            'website' => $website,
            'visitor_ip' => $visitorIp,
            'status' => $status
        ]);
        $comment->subscribe($request);

        return $comment;
    }


    // public function getUpdatedAtAttribute()
    // {
    //     if(Auth::guest()) {
    //         $updated_at = new Carbon($this->attributes['updated_at'], 'UTC');
    //         return $updated_at->format('Y-m-d\TH:i\Z');
    //     }
    //     else {
    //         $timezone = Auth::user()->timezone;
    //         return Timezone::convertFromUTC($this->attributes['updated_at'], $timezone, 'Y-m-d H:i');
    //     }
    // }

    // public function getCreatedAtAttribute()
    // {
    //     if(Auth::guest()) {
    //         $created_at = new Carbon($this->attributes['created_at'], 'UTC');
    //         return $created_at->format('Y-m-d\TH:i\Z');
    //     }
    //     else {
    //         $timezone = Auth::user()->timezone;
    //         return Timezone::convertFromUTC($this->attributes['created_at'], $timezone, 'Y-m-d H:i');
    //     }
    // }

    // public function getPublishedAtAttribute()
    // {
    //     return $this->attributes['published_at'];
    // }

    public function setPublishedAtAttribute($publishAt)
    {
        if($publishAt) {

                $this->attributes['published_at'] = new Carbon($publishAt); //Timezone::convertToUTC($published_at_temp->addSeconds(59), Auth::user()->timezone);
        }
        else {
            if(!isset($this->published_at))
                $this->attributes['published_at'] = Carbon::now();
        }
    }

    public function processTermObject($term = [], $taxonomyId = null)
    {
        $id = null;
        if(!array_key_exists('id', $term)) {
            $newTerm = Term::firstOrCreate([
                'name' => strtolower($term['name']),
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
