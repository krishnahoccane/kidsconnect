<?php

namespace App\Http\Controllers\Api;

use App\Models\roles;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class rolesController extends Controller
{
    public function index()
    {

        $roles = roles::all();

        if ($roles->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $roles
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }


    }

    public function create(Request $request)
    {

        $validate = Validator::make($request->all(), [

            'role' => 'required|max:191'

        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'data' => $validate->errors()->all()
            ], 422);
        } else {
            $role = roles::firstOrCreate([
                'role' => $request->role,
            ]);

            if ($role->wasRecentlyCreated) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Role created successfully',
                    'data' => $role
                ], 200);
            } else {
                return response()->json([
                    'status' => 409,
                    'message' => 'Role already exists'
                ], 409);
            }
        }


    }

    public function show($id)
    {

        $roles = roles::find($id);

        if ($roles) {

            return response()->json([
                'status' => 200,
                'data' => $roles
            ], 200);

        } else {

            return response()->json([
                'status' => 404,
                'message' => 'No such record found'
            ], 404);

        }


    }

    public function update(Request $request, int $id)
    {

        $validate = Validator::make($request->all(), [

            'role' => ['required', 'max:191', Rule::unique('roles')->ignore($id)],

        ]);

        if ($validate->fails()) {

            return response()->json([
                'status' => 422,
                'message' => $validate->messages()
            ], 422);

        } else {

            $role = roles::find($id);

            if ($role) {

                $role->update(['role' => $request->role]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Role Updated successfully',
                    'data' => $role
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No role found'
                ], 404);
            }
        }

    }

    public function delete(Request $request, int $id)
    {
        $role = roles::find($id);

        if (!$role) {
            return response()->json(['status' => 404, 'message' => "Given role for delete is not found"], 404);
        }

        $role->delete();

        return response()->json(['status' => 200, 'message' => "Given role is deleted Successfully"], 200);
    }

}
