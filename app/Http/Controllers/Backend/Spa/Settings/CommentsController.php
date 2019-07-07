<?php

namespace App\Http\Controllers\Backend\Spa\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\Website;
use App\Services\WebsiteService;

class CommentsController extends Controller
{
    protected $websiteService;

    public function __construct(WebsiteService $websiteService)
	{
        $this->websiteService = $websiteService;
    }
    
    public function index()
    {
        $settings = get_website_setting('comments');

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ], 200);
    }

    public function store(Request $request)
    {
        $this->websiteService->updateSettings('comments', $request->settings);

        return response()->json(['status' => 'success'], 200);
    }
}
