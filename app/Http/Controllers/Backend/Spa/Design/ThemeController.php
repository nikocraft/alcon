<?php

namespace App\Http\Controllers\Backend\Spa\Design;

use File;
use ThemeManager;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\Setting;
use App\Models\Core\Design\Theme;
use App\Models\Core\Settings\Website;
use App\Services\Zip\ZipArchive;
use App\Http\Resources\ThemeResource;

use App\Services\WebsiteService;
use App\Services\Themes\ThemeService;

class ThemeController extends Controller
{
    protected $websiteService;
    protected $themeservice;

    public function __construct(WebsiteService $websiteService, ThemeService $themeservice)
    {
        $this->websiteService = $websiteService;
        $this->themeservice = $themeservice;
    }

    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 25);
        // $themes = Theme::paginate($per_page);
        $themes = Theme::where('folder', '!=', 'admin')->get();

        $websiteSettings = $this->websiteService->getSettings();
        $activeThemeId = data_get($websiteSettings, 'website.activeTheme');

        return ThemeResource::collection($themes)
            ->additional([
                'activeTheme' => $activeThemeId
            ]);
    }

    public function setActive(Request $request)
    {
        $this->websiteService->updateSetting('website', 'activeTheme', $request->id);
        return response()->json(null, 200);
    }

    public function upload(Request $request) {
        if($request->hasFile('file')) {
            $file = $request->file('file');

            $rules = array('theme' => 'required|mimes:zip');
            $validator = Validator::make(array('theme'=> $file), $rules);

            if($validator->passes()) {
                // save theme zip file inside storage/app/themes and get a path+filename back
                $filename = $file->store('themes');
                $themePath = storage_path('app' . DIRECTORY_SEPARATOR . $filename);

                $return = $this->themeservice->installTheme($themePath);

                return response()->json([
                    'message' => $return->message,
                    'data' => [
                        'id' => $return->id
                    ]
                ], $return->code);
            }
        }
    }
}
