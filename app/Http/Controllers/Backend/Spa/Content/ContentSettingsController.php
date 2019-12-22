<?php

namespace App\Http\Controllers\Backend\Spa\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Http\Resources\SettingResource;

class ContentSettingsController extends Controller
{
    protected $websiteService;

    public function __construct(SettingsService $websiteService)
	{
        $this->websiteService = $websiteService;
    }
    
    public function storeIndexSettings(Request $request) {
        $this->websiteService->updateSettings('atlas.content.indexPage', $request->settings);

        $websiteSettings = $this->websiteService->getSettings();
        $settings = data_get($websiteSettings, 'atlas.content.indexPage');
        return response()->json(['data' => $settings]);
    }

    public function storeEditorSettings(Request $request) {
        $this->websiteService->updateSettings('atlas.content.editor', $request->settings);

        $websiteSettings = $this->websiteService->getSettings();
        $settings = data_get($websiteSettings, 'atlas.content.editor');
        return response()->json(['data' => $settings]);
    }
}
