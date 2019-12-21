<?php

namespace App\Http\Controllers\Backend\Spa\Design;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Design\WidgetGroup;
use App\Models\Core\Content\ContentType;
use App\Services\ThemeService;
use App\Services\WidgetService;
use App\Http\Resources\WidgetGroupResource;
use App\Http\Resources\ThemeSettingsResource;

class WidgetController extends Controller
{
    protected $themeservice;
    protected $widgetService;

    public function __construct(ThemeService $themeservice, WidgetService $widgetService)
	{
        $this->themeservice = $themeservice;
        $this->widgetService = $widgetService;
    }
    
    public function index()
    {
        $widgets = WidgetGroup::all();
        return WidgetGroupResource::collection($widgets);
    }

    public function getContentTree(Request $request)
    {
        $types = ContentType::with('content')->get();

        $types = $types->map(function ($object) {
            return [
                'id' => 'content_type_'.$object->id,
                'realId' => $object->id,
                'label' => $object->name,
                'type' => 'content_type',
                'children' => $object->content->map(function ($cObj) {
                    return [
                        'id' => 'content_'.$cObj->id,
                        'realId' => $cObj->id,
                        'label' => $cObj->title,
                        'type' => 'content'
                    ];
                })
            ];
        });

        return response()->json(['data' => $types]);
    }

    public function show($id)
    {
        $areas = $this->themeservice->getActiveThemeSection('widgetAreas');

        $widgetGroup = WidgetGroup::with('widgets')->where('id', $id)->first();

        // sort the widgets
        // $widget->blocks = $widget->blocks->sortBy('order');

        return (new WidgetGroupResource($widgetGroup))->additional(compact('areas'));
    }

    public function getAreas()
    {
        $theme = $this->themeservice->getActiveTheme();
        $areas = $this->themeservice->getSection($theme->id, 'widgetAreas');

        return ThemeSettingsResource::collection($areas);
    }

    public function store(Request $request)
    {
        $widgetGroup = $this->widgetService->save($request);

        $widgetGroup = WidgetGroup::with('widgets')->where('id', $widgetGroup->id)->first();

        return new WidgetGroupResource($widgetGroup);
    }

    public function destroy($id)
    {
        $content = WidgetGroup::find($id);
        $content->delete();
        return response()->json([], 200);
    }
}
