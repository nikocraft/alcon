<?php

namespace App\Http\Controllers\Backend\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\Website;
use App\Models\User;

class ActivateController extends Controller
{
    /**
     * Show activation form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showActivate(Request $request, $userId)
    {
        $signatureValid = 'false';
        if ($request->hasValidSignature()) {
            $signatureValid = 'true';
        }
        $forwardingQuery = $request->all();

        $settingsData = Website::getSettingsDataWithMeta();
        $settings = $settingsData['adminCustomLogin'];
        return view('auth.activate', compact('settings', 'userId', 'signatureValid', 'forwardingQuery'));
    }

    /**
     * Activate User.
     *
     */
    public function activate(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:8'
        ]);
        $user = User::find($request->user);
        if ($request->hasValidSignature()) {
            if($user->is_activated == false) {
                $user->password = bcrypt($request->password);
                $user->is_activated = true;
                $user->save();
            }
            return response()->json([
                'sucess' => 'Activated.'
            ], 200);
        } else {
            // do nothing...
        }
    }
}
