<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\Sluggable;
use App\Models\Traits\Taggable;
use App\Models\Traits\HasBlocks;

class Template extends Model
{
    use Sluggable;
    use Taggable;
    use HasBlocks;

    protected $table = "templates";
    protected $fillable = ['name', 'description', 'slug', 'screenshot', 'meta'];

    public $casts = [
        'meta' => 'array',
    ];
}
