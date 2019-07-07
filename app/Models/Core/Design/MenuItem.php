<?php

namespace App\Models\Core\Design;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = "menu_items";

    protected $casts = [
        'meta' => 'array'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function subItems()
    {
        return $this->hasMany(self::class, 'parent_id', 'unique_id');
    }

    static public function set($menuId, $itemData, $parentId)
    {
        $item = MenuItem::where('unique_id', $itemData->uniqueId)->where('menu_id', $menuId)->first();

        if($item) {
            $item->title = $itemData->content['title'];
            $item->url = $itemData->content['url'];
            $item->order = $itemData->order;
            $item->parent_id = $parentId;
            $item->update();
        } else {
            $item = new MenuItem();
            $item->menu_id = $menuId;
            $item->type = $itemData->type;
            $item->title = $itemData->content['title'];
            $item->url = $itemData->content['url'];
            $item->order = $itemData->order;
            $item->unique_id = $itemData->uniqueId;
            $item->parent_id = $parentId;
            $item->save();
        }

        return $item;
    }
}
