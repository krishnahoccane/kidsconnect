<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\defaultStatus;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class defalutStatusController extends Controller
{
    public function index()
    {
        $allStatus = defaultStatus::all();
        if ($allStatus->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $allStatus
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
        $validatestatus = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validatestatus->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validatestatus->messages()
            ], 422);
        } else {
            $allstatus = defaultStatus::firstOrCreate(['name' => $request->name]);

            if ($allstatus->wasRecentlyCreated) {
                return response()->json([
                    'status' => 200,
                    'data' => 'Status is created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 403,
                    'message' => 'Status Already Exist'
                ], 403);
            }
        }
    }

    public function show($id)
    {

        $allstatus = defaultStatus::find($id);

        // print_r($allstatus);

        if ($allstatus) {
            return response()->json([
                'status' => 200,
                'data' => $allstatus
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'data' => 'No such record found'
            ], 404);
        }
    }

    public function update(Request $request, int $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                // here in unique function we are giving the table name which is created by migrations
                'name' => ['required', Rule::unique('status')->ignore($id)],
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validate->messages()
            ], 422);
        } else {

            $allstatus = defaultStatus::find($id);

            if ($allstatus) {
                $allstatus->update(['name' => $request->name]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Record updated successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No status found'
                ], 404);
            }
        }
    }

    public function destroy($id)
    {
        $allstatus = defaultStatus::find($id);

        if ($allstatus) {
            $allstatus->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Record deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No status found'
            ], 404);
        }
    }

}
