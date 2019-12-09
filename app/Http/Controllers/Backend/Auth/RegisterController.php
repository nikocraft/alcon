<?php

namespace App\Http\Controllers\Backend\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Check if user registrations are allowed
     *
     * @var boolean
     */
    protected $allowRegistrations = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->allowRegistrations = get_website_setting('members.allowRegistrations');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if($this->allowRegistrations) {
            return view('auth.register');
        } else {
            abort(404);
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if($this->allowRegistrations) {
            $this->validator($request->all())->validate();

            event(new Registered($user = $this->create($request->all())));

            // $this->guard()->login($user);

            return $this->registered($request, $user);
        }
    }


    protected function registered(Request $request, $user)
    {
        return response()->json([
            'message' => 'User account has been created successfully.'
        ], 200);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'username' => 'required|string|min:3|max:30|unique:users',
            'email' => 'required|string|email|max:40|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validator->sometimes('firstname', 'required|alpha|min:2|max:25', function ($input) {
            return get_website_setting('members.requireFullname', false);
        });

        $validator->sometimes('lastname', 'required|alpha|min:2|max:25', function ($input) {
            return get_website_setting('members.requireFullname', false);
        });

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $member = Role::where('name', 'member')->first();
        $user = User::create([
            'firstname' => $data['firstname'] ?: null,
            'lastname' => $data['lastname'] ?: null,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'approved' => get_website_setting('members.autoApprove', false)
        ]);

        // attach default role
        $user->attachRole($member);

        $user->notify(new \App\Notifications\Users\ActivateUserNotification($user));

        return $user;
    }
}
