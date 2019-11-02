<?php

namespace App\Models\Core\Media;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Core\Base\Image as BaseImage;
use App\Models\Traits\Sluggable;
use App\Models\Traits\Taggable;

class Image extends Model
{
    use Sluggable;
    use Taggable;

    protected $table = 'images';
    protected $fillable = ['title', 'user_id', 'slug', 'caption', 'alt'];
    protected $appends = ['original', 'thumb', 'medium', 'large'];

    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
        'published_at'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    static public function imageUrl($imageSize, $image) {
        switch ($imageSize) {
            case 'original':
                return '/' . $image->path .  $image->filename . '.' .  $image->extension;
            break;

            case 'large':
                return '/' .  $image->path .  $image->filename . '_large.' .  $image->extension;
            break;

            case 'medium':
                return '/' .  $image->path .  $image->filename . '_medium.' .  $image->extension;
            break;

            case 'thumb':
                return '/' .  $image->path .  $image->filename . '_thumb.' .  $image->extension;
            break;

            default:
                return '/' .  $image->path .  $image->filename . '_large.' .  $image->extension;
        }
    }

    public function getOriginalAttribute()
    {
        return asset($this->path . $this->filename . '.' . $this->extension);
    }

    public function getLargeAttribute()
    {
        return asset($this->path . $this->filename . '_large.' . $this->extension);
    }

    public function getMediumAttribute()
    {
        return asset($this->path . $this->filename . '_medium.' . $this->extension);
    }

    public function getThumbAttribute()
    {
        return asset($this->path . $this->filename . '_thumb.' . $this->extension);
    }

    public function scopeNewer($query)
    {
        $query->orderBy('id', 'desc');
    }

    public function scopeOlder($query)
    {
        $query->orderBy('id', 'asc');
    }
}