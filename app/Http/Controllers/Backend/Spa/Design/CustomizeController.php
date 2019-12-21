<?php

namespace App\Http\Controllers\Backend\Spa\Design;

use ThemeManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ThemeResource;
use App\Services\ThemeService;

class CustomizeController extends Controller
{
    protected $themeservice;

    public function __construct(ThemeService $themeservice)
	{
		$this->themeservice = $themeservice;
	}

    public function getActive(Request $request)
    {
        $theme = $this->themeservice->getActiveTheme();
        return new ThemeResource($theme);
    }

    public function updateActive(Request $request)
    {
        $theme = $this->themeservice->getActiveTheme();
        $themeName = $theme->name;

        ThemeManager::set($themeName);

        foreach ($request->settings as $key => $setting) {
            $theme->updateSetting($setting['id'], $setting['value']);
        }

        $themeSettings = $this->themeservice->getSettings($theme->id);
        $this->buildThemeStyles($theme, $themeSettings);

        // touch update_at for this theme so that we know when it was last customized
        $theme->touch();

        return response()->json($themeSettings, 200);
    }

    private function buildThemeStyles($theme, $settings)
    {
        $themeName = $theme->name;
        $customizeCss = view('helpers.theme-css-customizer', compact(['settings']))->render();

        // beautify css
        $customizeCss = preg_replace('/^\h+(?=})|^(\h{4})\h*|(\R|\t)\2+/m', '$1$2', $customizeCss);
        $customizeCss = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $customizeCss);
        $customizeCss = str_replace(['}'], ["}\r\n"], $customizeCss);
        $customizeCss = preg_replace('/^(?(?! *})( {4}))\h*|(?|(?:(\R(?<!}\n))\h*)+|(\t))\2+/m', '$1$2', $customizeCss);
        $customizeCss = str_replace(['}'], ["}\r\n"], $customizeCss);

        // save customized theme css into a file that can be loaded at run time
        file_put_contents(public_path().'/themes/'.$themeName.'/css/customize.css', $customizeCss);
        return true;
    }
}
