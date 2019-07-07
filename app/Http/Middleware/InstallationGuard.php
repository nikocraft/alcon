<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Schema;
use App\Models\Core\Settings\Website;

class InstallationGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($this->testDbConnection()
            && Schema::hasTable( (new Website)->getTable() )
        ) {
            $installedFlag = get_website_setting('website.installed');
            if($installedFlag) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }

    protected function testDbConnection()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
