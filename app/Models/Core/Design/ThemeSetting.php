<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $fillable = ['theme_id', 'parent_id', 'section', 'key', 'label', 'value', 'type', 'meta'];
    protected $guarded = ['id'];
    protected $hidden = ['theme_id'];

    public $timestamps = false;

    protected $casts = [
        'meta' => 'array',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(ThemeSetting::class, 'id', 'parent_id');
    }

    public function settings()
    {
        return $this->hasMany(ThemeSetting::class, 'parent_id', 'id')->orderBy('id', 'asc');
    }
}
