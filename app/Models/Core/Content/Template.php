<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\Sluggable;
use App\Models\Traits\Taggable;
use App\Models\Traits\HasBlocks;
use App\Models\Traits\HasSchemalessAttributes;

class Template extends Model
{
    use Sluggable;
    use Taggable;
    use HasBlocks;
    use HasSchemalessAttributes;

    protected $table = "templates";
    protected $fillable = ['name', 'description', 'slug', 'screenshot', 'settings'];

    public $casts = [
        'settings' => 'array',
    ];
}
