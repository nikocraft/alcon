<?php

namespace App\Http\Controllers\Backend\Spa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\AdminMenu;
use App\Models\Core\Content\ContentType;

class SpaController extends Controller
{

    public function index()
    {
        $adminMenu = AdminMenu::orderBy('order')->get();
        $contentTypesList = ContentType::all()->toArray();
        $adminMenuArray = $adminMenu->toArray();
        $menuTree = $this->buildMenuTree($adminMenuArray);
        return view ('admin', compact('menuTree', 'contentTypesList'));
    }

    protected function buildMenuTree(array &$elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = $this->buildMenuTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
                unset($elements[$element['id']]);
            }
        }
        return array_values($branch);
    }
}
