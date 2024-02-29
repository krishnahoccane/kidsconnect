<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\defaultStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class defalutStatusController extends Controller
{
    public function index(){
        $allStatus = defaultStatus::all();
        if($allStatus->count()>0){
            return response()->json([
            'status'=>200,
            'data'=>$allStatus
            ],200);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'No Data Found'
                ],404);
        }
    }

    public function create(Request $request){
        $validatestatus = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if($validatestatus->fails()){
            return response()->json([
                'status' => 422,
                'message' => $validatestatus->messages()
            ],422);
        }else{
            $allstatus = defaultStatus::firstOrCreate(['name' =>$request->name]);

            if($allstatus->wasRecentlyCreated)
            {
                return response()->json([
                    'status'=>200,
                    'data'=>'Status is created successfully'
                    ],200);
            }else{
                return response()->json([
                    'status'=>500,
                    'message'=>'Status Already Exist'
                    ],500);
            }
        }
    }

    public function show(){
        
    }

}
