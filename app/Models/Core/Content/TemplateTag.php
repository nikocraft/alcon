<?php

namespace App\Models\Core\Media;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Sluggable;
// use App\Models\Traits\Tagable;

class TemplateTag extends Model
{
    use Sluggable;
    // use Tagable;

    protected $fillable = ['title', 'slug'];
    protected $table = 'tags';

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'template_tag');
    }

    // Add any new tags
    static public function processNewTags($tags)
    {
        $data = array();

        if($tags) {
            foreach($tags as $key => $value) {
                if(!is_numeric($value)) {
                    $tag = new TemplateTag();
                    $tag->title = $value;
                    $tag->slug = $tag->slugify($value);
                    $tag->save();
                    $data += [$key => $tag->id];
                }
            }

            $temp = array();
            $temp = $tags;

            foreach ($data as $key => $value) {
                unset($temp[$key]);
            }

            $tags = array_merge($temp,$data);
        }
        return $tags;
    }
}
