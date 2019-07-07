<?php

namespace App\Http\Controllers\Backend\Spa\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\Website;
use App\Services\WebsiteService;

class AdminController extends Controller
{
    protected $websiteService;

    public function __construct(WebsiteService $websiteService)
	{
        $this->websiteService = $websiteService;
	}

    public function index()
    {
        $settings = get_website_setting('admin');

        return response()->json([
            'data' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $this->websiteService->updateSettings('admin', $request->settings);

        return response()->json(['status' => 'success'], 200);
    }
}
