<?php

namespace App\Http\Controllers\Backend\Spa\Design;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Settings\Website;
use App\Models\Core\Design\WidgetGroup;
use App\Models\Core\Content\ContentType;

use App\Services\Themes\ThemeService;
use App\Services\WidgetService;

use App\Http\Resources\WidgetResource;
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
        return WidgetResource::collection($widgets);
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

        $widget = WidgetGroup::with('widgets')->where('id', $id)->first();

        // sort the widgets
        // $widget->blocks = $widget->blocks->sortBy('order');

        return (new WidgetResource($widget))->additional(compact('areas'));
    }

    public function getAreas()
    {
        $theme = $this->themeservice->getActiveTheme();
        $areas = $this->themeservice->getSection($theme->id, 'widgetAreas');

        return ThemeSettingsResource::collection($areas);
    }

    public function store(Request $request)
    {
        $widget = $this->widgetService->save($request);

        $widget = WidgetGroup::with('widgets')->where('id', $widget->id)->first();

        return new WidgetResource($widget);
    }

    public function destroy($id)
    {
        $content = WidgetGroup::find($id);
        $content->delete();
        return response()->json([], 200);
    }
}
