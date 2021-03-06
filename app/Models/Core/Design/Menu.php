<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menus";

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id', 'id')->orderBy('order');
    }
}
