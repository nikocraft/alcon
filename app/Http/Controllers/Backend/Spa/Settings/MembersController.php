<?php

namespace App\Http\Controllers\Backend\Spa\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WebsiteService;

class MembersController extends Controller
{
    protected $websiteService;

    public function __construct(WebsiteService $websiteService)
	{
        $this->websiteService = $websiteService;
    }
    
    public function index()
    {
        $settings = get_website_setting('members');

        return response()->json([
            'data' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $this->websiteService->updateSettings('members', $request->settings);

        return response()->json(['status' => 'success'], 200);
    }
}
