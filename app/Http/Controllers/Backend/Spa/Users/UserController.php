<?php

namespace App\Http\Controllers\Backend\Spa\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;

use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use Session;

use App\Http\Resources\UserResource;
use App\Http\Resources\RoleResource;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sorting = $request->sorting;
        $order = $request->order;
        $search = $request->search;
        $filter = $request->filter;

        $append = array();
        \DB::connection()->enableQueryLog();
        $users = User::with(['roles', 'permissions']);

        if($sorting){
            switch ($sorting) {
                case 'id':
                    $users->orderBy('id', $order);
                break;

                case 'name':
                    $users->orderBy('firstname', $order);
                break;

                case 'username':
                    $users->orderBy('username', $order);
                break;

                case 'email':
                    $users->orderBy('email', $order);
                break;

                default:
                    # code...
                break;
            }
            $append += array('sorting' => $sorting);
            $append += array('order' => $order);
        }

        if($search){
            switch ($filter) {
                case 'name':
                    $users->where('firstname', 'LIKE', '%'. $search . '%')->orWhere('lastname', 'LIKE', '%'. $search . '%');
                break;

                case 'username':
                    $users->where('username', 'LIKE', '%'. $search . '%');
                break;

                case 'email':
                    $users->where('email', 'LIKE', '%'. $search . '%');
                break;

                case 'role':
                    $users->whereRoleIs($search);
                break;

                default:
                    # code...
                break;
            }
            $append += array('search' => $search);
            $append += array('filter' => $filter);
        }

        $users = $users->paginate(20);
        $users->appends($append);
        $query = \DB::getQueryLog();

        if ($users) {
            return UserResource::collection($users);
        } else {
            return response()->json([
                'message' => 'Could not find any user.'
            ], 404);
        }
    }

    public function show(Request $request, $id)
    {
        $authUser = Auth::check() ? Auth::user() : User::find(1);

        $user = User::with(array(
            'roles' => function ($query) {
                $query->select('id', 'display_name')->with(array(
                    'permissions' => function ($query) {
                        $query->select('id', 'display_name');
                    }
                ));
            }
        ))->with('permissions')->find($id);
        $permissions = Permission::where('name', '!=', 'crud-role', 'AND')->where('name', '!=', 'crud-permission')->get();

        if($authUser->hasRole('super'))
            $roles = Role::with(array('permissions' => function ($query) {
                            $query->select('id', 'display_name');
                        }
                    ))->select('id', 'display_name')->get();
        elseif ($authUser->hasRole('admin'))
            $roles = Role::with(array('permissions' => function ($query) {
                            $query->select('id', 'display_name');
                        }
                    ))->select('id', 'display_name')->where('name', '!=', 'super')->get();

        if($user) {
            return (new UserResource($user))
                ->additional([
                    'permissions' => $permissions,
                    'roles' => $roles,
                ]);
        } else {
            return response()->json([
                'message' => 'Could not find this user. Perhaps the user was deleted.'
            ], 401);
        }
    }

    public function store(Request $request)
    {
        $messages = [
            'password.regex' => 'Password needs to have 1 lowercase, 1 upercase and 1 Non-alphanumeric character.',
            'roles.required' => 'Atleast one role must be assigned.',
        ];

        $validator = Validator::make($request->all(), [
                'firstname' => 'nullable|alpha|min:2|max:25',
                'lastname' => 'nullable|alpha|min:2|max:25',
                'username' => 'required|alpha_dash|min:3|max:20|unique:users',
                'email' => 'required|email|unique:users,email,',
                'bio' => 'sometimes|min:5',
                'password' => 'required|min:8',
                // 'password' => 'bail|required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|min:6|confirmed',
                'roles' => 'required|array',
                'permissions' => 'sometimes|array',
            ],
            $messages
        );

        $validator->validate();

        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->username = $request->username;
        $user->slug = $user->username;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->approved = true;
        $user->password = bcrypt($request->password);
        $user->save();

        $user->roles()->sync($request->roles, true);

        if($request->permissions)
            $user->permissions()->sync($request->permissions, true);

        if($user) {
            $user->notify(new \App\Notifications\Users\ActivateUserNotification($user));
            return new UserResource($user);
        } else {
            return response()->json([
                'message' => 'For some reason user could not be created.'
            ], 403);
        }
    }

    public function update(Request $request)
    {
        $messages = [
            'password.regex' => 'Password needs to have 1 lowercase, 1 upercase and 1 Non-alphanumeric character.',
        ];

        $validator = Validator::make($request->all(), [
                'firstname' => 'nullable|alpha|min:2|max:25',
                'lastname' => 'nullable|alpha|min:2|max:25',
                'username' => 'required|alpha_dash|min:3|max:20',
                'email' => 'required|email|unique:users,email,' .$request->id,
                'bio' => 'sometimes|max:300',
                'roles' => 'required|array',
                'permissions' => 'sometimes|array',
            ],
            $messages
        );

        $validator->validate();

        $user = User::find($request->id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->approved = $request->approved;
        $user->bio = $request->bio;

        // if($request->password)
        //     $user->password = bcrypt($request->password);

        $user->save();

        if($request->roles)
            $user->roles()->sync($request->roles, true);

        if($request->extrapermissions)
            $user->permissions()->sync($request->extrapermissions, true);
        else
            $user->permissions()->detach();

        $user = User::with(array(
            'roles' => function ($query) {
                $query->select('id', 'display_name')->with(array(
                    'permissions' => function ($query) {
                        $query->select('id', 'display_name');
                    }
                ));
            }
        ))->with('permissions')->find($request->id);

        if($user){
            return new UserResource($user);
        }
    }

    public function getAllRoles(Request $request) {
        $roles = Role::with(array('permissions' => function ($query) {
                            $query->select('id', 'display_name');
                        }
                    ))->select('id', 'display_name')->get();
        $permissions = Permission::all();

        if($roles){
            return RoleResource::collection($roles)
                ->additional([
                    'permissions' => $permissions
                ]);
        } else{
            return response()->json([
                'message' => 'Could not find any roles.'
            ], 404);
        }
    }

    public function destroy(Request $request, $id) {
        $user = User::findOrFail($id);
        $authUserId = Auth::check() ? Auth::user()->id : 1;

        // Make sure admin is not trying to delete him self, this is not allowed
        if($authUserId != $user->id) {
            if($request->action == 'transfer') {
                $newOwner = User::find($request->transfer_to);
                $user->posts->each->update([ 'user_id' => $newOwner->id ]);

                // Fetch the user again so that we do not delete any old posts
                $user = User::find($request->id);
            }

            // Delete user
            if($user->delete()){
                return response()->json([
                    'message' => 'deleted',
                ], 201);
            } else{
                return response()->json([
                    'message' => 'Could not delete the user. Does user still exists?'
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'You cannot delete your self. Asimov\'s third law: An admin must protect his own existence.'
            ], 403);
        }
    }
}
