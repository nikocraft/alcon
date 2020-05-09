<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Core\Content\Content;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

use Laratrust\Traits\LaratrustUserTrait;
use App\Models\Traits\Sluggable;

class User extends Authenticatable
{
    use Sluggable;
    use Notifiable;
    use LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'slug', 'username', 'email', 'password', 'bio', 'meta', 'activated', 'approved' , 'approved_at'
    ];

    protected $casts = [
        'activated' => 'boolean', 
        'approved' => 'boolean',
        'activated_at' => 'datetime'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    public function getMetaAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'meta');
    }

    public function scopeWithMeta(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('meta');
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = $value;
        $this->attributes['slug'] = $this->slugify($value);
    }

    /**
    * Get all user roles.
    *
    * @return bool
    */
    public function getUserRolesAttribute()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            array_push($roles, $role['name']);
        }
     return $roles;
    }

    /**
    * Get all user permissions.
    *
    * @return bool
    */
    public function getUserPermissionsAttribute()
    {
        $permissions = [];
        foreach ($this->allPermissions() as $permission) {
            array_push($permissions, $permission['name']);
        }
     return $permissions;
    }

    public function gravatar()
    {
        $email = "nermion@gmail.com";
        $default = "http://i.imgur.com/oPYEzsj.png";
        $size = 100;

        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
    }

    public function delete()
    {
        $this->content->each->delete();
        return parent::delete();
    }

    public function content()
    {
        return $this->hasMany(Content::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
