<?php

namespace App\Http\Controllers\Backend\Spa\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\Website;
use App\Services\WebsiteService;
use App\Http\Resources\SettingResource;

class ContentSettingsController extends Controller
{
    protected $websiteService;

    public function __construct(WebsiteService $websiteService)
	{
        $this->websiteService = $websiteService;
    }
    
    public function storeIndexSettings(Request $request) {
        $this->websiteService->updateSettings('contentIndex', $request->settings);

        $websiteSettings = $this->websiteService->getSettings();
        $settings = data_get($websiteSettings, 'contentIndex');
        return response()->json(['data' => $settings], 200);
    }

    public function storeEditorSettings(Request $request) {
        $this->websiteService->updateSettings('contentEditor', $request->settings);

        $websiteSettings = $this->websiteService->getSettings();
        $settings = data_get($websiteSettings, 'contentEditor');
        return response()->json(['data' => $settings], 200);
    }
}
