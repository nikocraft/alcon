<?php

namespace App\Http\Controllers\Backend\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\Website;
use App\Models\User;
use Carbon\Carbon;

class ActivateController extends Controller
{
    /**
     * Show activated form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showActivatedForm(Request $request, $userId)
    {
        $activated = false;
        $signatureValid = $request->hasValidSignature();

        if ($signatureValid) {
            $user = User::find($userId);
            if($user && is_null($user->activated_at)) {
                $user->activated = true;
                $user->activated_at = Carbon::now();
                $user->save();
                $activated = true;
            } else if($user) {
                $activated = true;
            }
            $autoApprove = (bool) $user->approved;
        }
        return view('auth.activate', compact('activated', 'signatureValid', 'autoApprove'));
    }
}
