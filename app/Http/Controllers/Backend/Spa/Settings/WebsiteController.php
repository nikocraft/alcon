<?php

namespace App\Http\Controllers\Backend\Spa\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WebsiteService;

class WebsiteController extends Controller
{
    protected $websiteService;

    public function __construct(WebsiteService $websiteService)
	{
        $this->websiteService = $websiteService;
    }
    
    public function index()
    {
        $settings = get_website_setting('website.general');

        return response()->json([
            'data' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $settings = [ 'general' => $request->settings ];
        $this->websiteService->updateSettings('website', $settings);

        return response()->json([], 200);
    }
}
