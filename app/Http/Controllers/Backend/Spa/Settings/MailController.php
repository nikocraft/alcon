<?php

namespace App\Http\Controllers\Backend\Spa\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Artisan;
use App\Services\SettingsService;

class MailController extends Controller
{
    protected $websiteService;

    public function __construct(SettingsService $websiteService)
	{
        $this->websiteService = $websiteService;
    }
    
    public function index()
    {
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        $settings = [
            'driver' => env('MAIL_DRIVER', null),
            'host' => env('MAIL_HOST', null),
            'port' => env('MAIL_PORT', null),
            'username' => env('MAIL_USERNAME', null),
            'password' => env('MAIL_PASSWORD', null),
            'encryption' => env('MAIL_ENCRYPTION', null)
        ];

        return response()->json([
            'data' => $settings
        ]);
    }

    public function store(Request $request)
    {
        $mailEnvData = [
            'MAIL_DRIVER' => $request->driver,
            'MAIL_HOST' => $request->host,
            'MAIL_PORT' => $request->port,
            'MAIL_USERNAME' => $request->username,
            'MAIL_PASSWORD' => $request->password,
            'MAIL_ENCRYPTION' => $request->encryption
        ];

        set_env_vars($mailEnvData);

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return response()->json([
            'status' => 'success',
        ]);
    }
}
