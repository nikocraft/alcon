<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSettings;

class Block extends Model
{
    use HasSettings;

    protected $fillable = ['content', 'unique_id', 'type', 'order', 'settings', 'user_id', 'parent_id', 'meta'];

    public $casts = [
        'settings' => 'array',
    ];

    public function setContentAttribute($value)
    {
        $content = null;
        if(isset($value)) {
            $content = is_array($value) ? json_encode($value) : $value;
        }
        $this->attributes['content'] = $content;
    }

    public function getContentAttribute($value)
    {
        if(is_json($value))
            return json_decode($value);
        else
            return $value;
    }

    public function getSettings()
    {
        return (object) $this->settings->all();
    }

    public function has_blocks()
    {
        return $this->morphTo();
    }
}
