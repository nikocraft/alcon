<?php

namespace App\Http\Controllers\Backend\Spa\Localization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;
use Cache;
use Carbon;

class LocalizationController extends Controller
{
    public function index(Request $request)
    {
        $strings = Cache::rememberForever('lang.js', function () {
            $lang = config('app.locale');
            $files   = glob(resource_path('lang/' . $lang . '/components/*.php'));
            $strings = [];
            foreach ($files as $file) {
                $name           = basename($file, '.php');
                $strings[$name] = require $file;
            }
            return $strings;
        });
        $javascript = 'window.i18n = ' . json_encode($strings) . ';';
        return response($javascript, 200, [
            'Cache-Control' => 'max-age=3600, public',
            'Last-Modified' => Carbon\Carbon::now(),
            'Content-Length' => strlen($javascript),
            'Content-Type' => 'text/javascript'
        ]);
    }
}
