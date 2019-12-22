<?php

namespace App\Http\Controllers\Backend\Spa\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SettingsService;

class AuthPageController extends Controller
{
    protected $websiteService;

    public function __construct(SettingsService $websiteService)
	{
        $this->websiteService = $websiteService;
    }
    
    public function index()
    {
        $settings = get_website_setting('website.adminAuthPage');

        return response()->json([
            'data' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $this->websiteService->updateSettings('website.adminAuthPage', $request->settings);

        return response()->json([], 200);
    }
}
