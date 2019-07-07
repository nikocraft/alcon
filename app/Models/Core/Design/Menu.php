<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Settings\Website;

class Menu extends Model
{
    protected $table = "menus";

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id', 'id')->orderBy('order');
    }
}
