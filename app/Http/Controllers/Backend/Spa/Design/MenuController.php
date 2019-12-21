<?php

namespace App\Http\Controllers\Backend\Spa\Design;

use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Design\Menu;
use App\Models\Core\Design\MenuItem;
use App\Http\Resources\Design\MenuResource;
use App\Http\Resources\Design\MenuItemResource;
use App\Http\Resources\ThemeSettingsResource;
use App\Services\WebsiteService;
use App\Services\ThemeService;

class MenuController extends Controller
{
    protected $websiteService;
    protected $themeservice;

    public function __construct(WebsiteService $websiteService, ThemeService $themeservice)
    {
        $this->websiteService = $websiteService;
        $this->themeservice = $themeservice;
    }

    public function index()
    {
        $menus = Menu::all();
        return MenuResource::collection($menus);
    }

    public function show($id)
    {
        $menu = Menu::with('items')->find($id);
        return (new MenuResource($menu));
    }

    public function destroy(Request $request, $id) {
        $menu = Menu::findOrFail($id);

        if($menu->delete()) {
            return response()->json(null, 200);
        } else {
            return response()->json([
                'message' => 'Could not delete.'
            ], 403);
        }
    }

    public function locations()
    {
        $websiteSettings = $this->websiteService->getSettings();
        $activeThemeId = data_get($websiteSettings, 'website.activeTheme');
        $locations = $this->themeservice->getSection($activeThemeId, 'menuLocations');

        return ThemeSettingsResource::collection($locations);
    }

    public function getItems($menuId)
    {
        $items = MenuItem::where('menu_id', $menuId)->get();

        return response()->json([
            'status' => 'success',
            'items' => $items
        ], 201);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:40',
            'location' => 'required'
        ]);

        // delete removed items
        foreach ($request->removedItems as $key => $itemId) {
            $item = MenuItem::where('unique_id', $itemId)->first();
            if($item) {
                $item->delete();
            }
        }

        if($request->id > 0) {
            $menu = Menu::where('id', $request->id)->first();
            Cache::forget('menu-location-' . $menu->location);
            Cache::forget('menu-id-' . $menu->id);
        } else {
            $menu = new Menu();
        }

        $menu->name = $request->name;
        $menu->location = $request->location;
        $menu->save();

        foreach($request->menuItems as $key => $itemData) {
            $itemData = (object) $itemData;
            $this->saveMenuItem($menu->id, $itemData);
        }

        $menu = Menu::with('items')->where('id', $menu->id)->first();

        return new MenuResource($menu);
    }

    protected function saveMenuItem($menuId, $itemData, $parentId = null)
    {
        $itemData = (object) $itemData;

        // create or update the block in database
        $item = MenuItem::set($menuId, $itemData, $parentId);

        // process sub blocks, done recursivly
        if(isset($itemData->subItems)) {
            for ($i=0; $i < count($itemData->subItems); $i++) {
                $this->saveMenuItem($menuId, $itemData->subItems[$i], $item->unique_id);
            }
        }
    }

}
