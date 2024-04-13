<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\petModel;
use App\Models\subscriberlogins;

use Illuminate\Http\Request;

class petController extends Controller
{
    //
    public function index(){
        
        $subKids = petModel::all();

        if ($subKids->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $subKids
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Data Found'
            ], 404);
        }
    }

    public function getKidsBySubscriberId($subscriberId)
    {
        // Retrieve kid data based on subscriber ID
        $kidMainSubId = petModel::where('MainSubscriberId', $subscriberId)->get();
        if ($kidMainSubId->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No kids found for the given subscriber ID'
            ], 404);
        }
    
        return response()->json([
            'status' => 200,
            'data' => $kidMainSubId
        ], 200);
    }
    public function updatePetProfile(Request $request, $petId)
    {
        $pet = petModel::find($petId);
    
        if (!$pet) {
            return response()->json([
                'status' => 404,
                'message' => 'Pet not found',
            ], 404);
        }
    
        // Update pet's profile information
        $pet->Name = $request->input('Name', $pet->Name);
        $pet->Breed = $request->input('Breed', $pet->Breed);
        $pet->gender = $request->input('Gender', $pet->gender); // Use lowercase 'gender'
        $pet->Dob = $request->input('Dob', $pet->Dob);
        $pet->Description = $request->input('Description', $pet->Description);
    
        // Check if a new profile image is uploaded
        if ($request->hasFile('ProfileImage')) {
            $profileImage = $request->file('ProfileImage');
            $path = 'uploads/profiles/';
            $fileName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->move($path, $fileName);
            $pet->ProfileImage = $path . $fileName;
        }
    
        // Save changes to the database
        $pet->save();
    
        return response()->json([
            'status' => 200,
            'message' => 'Pet profile updated successfully',
            'data' => $pet,
        ], 200);
    }
    
    
}
