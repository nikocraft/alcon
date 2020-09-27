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
        $settings = [
            'maildriver' => config('mail.driver'),
            'fromaddress' => config('mail.from.address'),
            'sendmail' => config('mail.sendmail'),
            'host' => config('mail.host'),
            'port' => config('mail.port'),
            'username' => config('mail.username'),
            'password' => config('mail.password'),
            'encryption' => config('mail.encryption'),
            'mailgundomain' => config('services.mailgun.domain'),
            'mailgunsecret' => config('services.mailgun.secret'),
            'mailgunendpoint' => config('services.mailgun.endpoint'),
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

        return response()->json([], 200);
    }
}
