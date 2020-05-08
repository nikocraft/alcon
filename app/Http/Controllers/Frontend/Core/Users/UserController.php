<?php

namespace App\Http\Controllers\Frontend\Core\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show($slug)
    {
        if (View::exists('user.profile')) {
            $user = User::with(array('content' => function($query) {
                $query->where('content_type_id', 2)->latest();
            }))->where('slug', $slug)->firstOrFail();

            return view('user.profile', ['user' => $user]);
        }
        else {
            return abort(404);
        }
    }
}
