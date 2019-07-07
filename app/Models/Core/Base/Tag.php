<?php

namespace App\Models\Core\Base;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Sluggable;

class Tag extends Model
{
    use Sluggable;

    protected $table = 'tags';
    protected $fillable = ['title', 'slug'];

    public function taggable()
    {
        return $this->morphTo();
    }

    public function images()
    {
        return $this->morphedByMany('App\Models\Core\Media\Image', 'taggable');
    }

    public function templates()
    {
        return $this->morphedByMany('App\Models\Core\Content\Template', 'taggable');
    }
}
