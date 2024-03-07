<?php

namespace App\Http\Controllers\Api;

use App\Models\subscriber;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class subscribersController extends Controller
{
    public function index()
    {

        $roles = subscriber::all();

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

            'typeid' => 'required',
            'status' => 'required',
            'isapproved' => 'required',
            'approvedon' => 'required',
            'approvedby' => 'required'

        ]);

        if ($validate->fails()) {

            return response()->json([
                'status' => 422,
                'message' => $validate->messages()
            ], 422);
        } else {
            $role = subscriber::firstOrCreate([
                'typeid' => $request->typeid,
                'status' => $request->status,
                'isapproved' => $request->isapproved,
                'approvedon' => $request->approvedon,
                'approvedby' => $request->approvedby
            ]);

            if ($role->wasRecentlyCreated) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Role created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'Role already exists'
                ], 403);
            }
        }

    }

    // public function show($id)
    // {

    //     $roles = roles::find($id);

    //     if ($roles) {

    //         return response()->json([
    //             'status' => 200,
    //             'data' => $roles
    //         ], 200);

    //     } else {

    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'No such record found'
    //         ], 404);

    //     }


    // }

    // public function update(Request $request, int $id)
    // {

    //     $validate = Validator::make($request->all(), [

    //         'role' => ['required', 'max:191', Rule::unique('roles')->ignore($id)],

    //     ]);

    //     if ($validate->fails()) {

    //         return response()->json([
    //             'status' => 422,
    //             'message' => $validate->messages()
    //         ], 422);

    //     } else {

    //         $role = roles::find($id);

    //         if ($role) {

    //             $role->update(['role' => $request->role]);

    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Role Updated successfully'
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => 'No role found'
    //             ], 404);
    //         }
    //     }

    // }

}
