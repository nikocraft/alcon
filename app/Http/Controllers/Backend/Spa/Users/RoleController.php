<?php

namespace App\Http\Controllers\Backend\Spa\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;

use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    public function index(Request $request) {
        $roles = Role::with(array('permissions' => function ($query) {
                            $query->select('id', 'display_name', 'name');
                        }
                    ))->select('id', 'display_name', 'name')->get();
        $permissions = Permission::all();

        return RoleResource::collection($roles)
            ->additional([
                'permissions' => $permissions
            ]);
    }

    public function unique(Request $request) {
        $exists = Role::where('name', $request->name)->exists();

        return response()->json([
            'valid' => !$exists,
            'message' => !$exists ? null : 'Name is not unique.',
        ], 200);
    }

    public function store(Request $request) {
        $role = new Role;
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->save();

        $permissions = Permission::orderBy('id')->whereIn('name', $request->permissions)->get();

        // Attach all permissions to the role
        $role->permissions()->sync($permissions);

        return new RoleResource($role);
    }

    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->save();

        $permissions = Permission::orderBy('id')->whereIn('name', $request->permissions)->get();

        // Attach all permissions to the role
        $role->permissions()->sync($permissions);

        return new RoleResource($role);
    }

    public function destroy(Request $request, $id) {
        $role = Role::findOrFail($id);

        if($role->delete()) {
            return response()->json(['message' => 'Successfully deleted'], 200);
        } else {
            return response()->json([
                'message' => 'Could not delete.'
            ], 403);
        }
    }
}
