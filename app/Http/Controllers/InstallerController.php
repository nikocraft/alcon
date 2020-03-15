<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Encryption\Encrypter;

use Artisan;
use DB;
use App\Models\Role;
use App\Models\User;
use App\Services\SettingsService;
use App\Services\LaraOneService;
use Carbon\Carbon;
use Theme;

class InstallerController extends Controller
{
    protected $settingsService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingsService $settingsService)
	{
        $this->settingsService = $settingsService;
        $this->installService = new LaraOneService;
    }

    /**
     * Show the installer.
     *
     * @return \Illuminate\Http\Response
     */
    public function showInstallWizard()
    {
        $envPath = app()->environmentFilePath();
        if(!file_exists($envPath)) {
            file_put_contents($envPath, '');
        }

        $envData = [
            'APP_KEY' => $this->generateRandomKey(),
            'APP_URL' => request()->root()
        ];

        $this->saveToEnv($envData);

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        $dbTestSuccess = $this->testDbConnection();

        Theme::set('admin'); 

        return view('installer');
    }

    public function saveEnvironmentSettings(Request $request)
    {
        $envData = [
            'APP_DEBUG' => "false",
            'APP_ENV' => 'production',
            'DB_DATABASE' => $request->database,
            'DB_USERNAME' => $request->username,
            'DB_PASSWORD' => $request->password,
            'DB_HOST'     => $request->host
        ];

        $this->saveToEnv($envData);

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        $dbTestSuccess = $this->testDbConnection();

        return response()->json([
            'status' => 'success',
            'dbTest' => $dbTestSuccess,
            'url' => request()->root()
        ], 200);
    }

    public function runInstaller(Request $request)
    {
        $success = true;
        $errorMessage = '';

        try {
            $lastVersion = $this->installService->getLastVersion();
            $this->installService->fetchDefaultTheme($lastVersion);
            Artisan::call('migrate', [ '--force' => true ]);
            Artisan::call('db:seed', [ '--class' => 'DatabaseSeeder', '--force' => true ]);
            $this->saveSiteSettings($request);
        } catch (\Exception $e) {
            $success = false;
            $errorMessage = $e->getMessage();
            // $this->resetDB();
        }

        return response()->json([
            'status' => $success ? 'success' : 'error',
            'data' => $errorMessage ? $errorMessage : 'Laraone installed!'
        ], $success ? 200 : 422);
    }

    private function saveSiteSettings($request)
    {
        if($request->siteTitle) {
            $this->settingsService->updateSetting('website.general.title', $request->siteTitle);
        }
        $super = Role::find(1);
        $user = User::create([
            // 'firstname' => $request->firstname,
            // 'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activated' => true,
            'activated_at' => Carbon::now(),
            'approved' => true
        ]);
        $user->attachRole($super);
        
        $cmsLastVersion = $this->installService->getLastVersion();
        $this->settingsService->updateSetting('cms.version', $cmsLastVersion);

        return response()->json([
            'status' => 'Success',
            'user' => $user
        ], 200);
    }

    private function resetDB()
    {
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            Schema::drop($table->Tables_in_pos);
        }
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    private function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            Encrypter::generateKey(config('app.cipher'))
        );
    }

    private function saveToEnv($data = [])
    {
        foreach($data as $key => $value) {
            set_env_var($key, $value);
        }

        return true;
    }

    private function testDbConnection()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
