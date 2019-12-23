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
            'maildriver' => env('MAIL_DRIVER', null),
            'fromaddress' => env('MAIL_FROM_ADDRESS', null),
            'sendmail' => env('MAIL_SENDMAIL', null),
            'host' => env('MAIL_HOST', null),
            'port' => env('MAIL_PORT', null),
            'username' => env('MAIL_USERNAME', null),
            'password' => env('MAIL_PASSWORD', null),
            'encryption' => env('MAIL_ENCRYPTION', null),
            'mailgundomain' => env('MAILGUN_DOMAIN', null),
            'mailgunsecret' => env('MAILGUN_SECRET', null),
            'mailgunendpoint' => env('MAILGUN_ENDPOINT', null),
        ];

        return response()->json([
            'data' => $settings
        ], 200);
    }

    public function store(Request $request)
    {
        $mailEnvData = [];

        switch ($request->maildriver) {
            case 'smtp':
                $mailEnvData = [
                    'MAIL_FROM_ADDRESS' => $request->fromaddress,
                    'MAIL_DRIVER' => $request->maildriver,
                    'MAIL_HOST' => $request->host,
                    'MAIL_PORT' => $request->port,
                    'MAIL_USERNAME' => $request->username,
                    'MAIL_PASSWORD' => $request->password,
                    'MAIL_ENCRYPTION' => $request->encryption
                ];
                break;

            case 'sendmail':
                $mailEnvData = [
                    'MAIL_FROM_ADDRESS' => $request->fromaddress,
                    'MAIL_DRIVER' => $request->maildriver,
                    'MAIL_SENDMAIL' => $request->sendmail,
                ];
                break;

            case 'mailgun':
                $mailEnvData = [
                    'MAIL_FROM_ADDRESS' => $request->fromaddress,
                    'MAIL_DRIVER' => $request->maildriver,
                    'MAILGUN_DOMAIN' => $request->mailgundomain,
                    'MAILGUN_SECRET' => $request->mailgunsecret,
                    'MAILGUN_ENDPOINT' => $request->mailgunendpoint,
                ];
                break;
            
            default:
                # code...
                break;
        }

        set_env_vars($mailEnvData);

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return response()->json([], 200);
    }
}
