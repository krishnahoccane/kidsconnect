<?php

namespace App\Http\Controllers\Api;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class adminController extends Controller
{
    public function showAdminUsers(){
        $adminUsers = Registration::all();
        
        if($adminUsers->count()>0){
            return response()->json([
                'status'=>200,
                'data' => $adminUsers
            ],200);
        }else{
            return response()->json([
                'status'=>404,
                'message' => 'No data available'
            ],404);
        }
    }

    public function show($id){
        
        $getAdminUsers = Registration::find($id);

        if($getAdminUsers){
            return response()->json([
               'status'=>200,
               'data'=>$getAdminUsers 
            ],200);
        }else{
            return response()->json([
                'status'=>404,
                'Message'=> 'No Record Found'
             ],404);
        }

    }
}
