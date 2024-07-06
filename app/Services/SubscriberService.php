<?php

// app/Services/SubscriberService.php
namespace App\Services;

use App\Models\subscriberlogins;
use App\Models\RegCodes;
use App\Models\subscribersKidModel;

class SubscriberService {


    public function showKidParent($kidId)
{
    // Find the kid by ID
    $kid = SubscribersKidModel::find($kidId);//1

    // Check if the kid exists
    if (!$kid) {
        return response()->json([
            'status' => 404,
            'message' => 'Kid not found'
        ], 404);
    }

    // Fetch primary parent
    $primaryParent = SubscriberLogins::where('id', $kid->MainSubscriberId)->first();

    // Check if the primary parent exists
    if (!$primaryParent) {
        return response()->json([
            'status' => 404,
            'message' => 'Primary parent not found'
        ], 404);
    }

    // Fetch secondary parents
    $secondaryParents = SubscriberLogins::where('MainSubscriberId', $kid->MainSubscriberId)
        ->where('id', '!=', $primaryParent->id)
        ->get();

    // Fetch siblings (other kids with the same MainSubscriberId)
    $siblings = SubscribersKidModel::where('MainSubscriberId', $kid->MainSubscriberId)
        ->where('id', '!=', $kid->id) // Exclude the current kid
        ->get();

    // Prepare response data including siblings and secondary parents
    $response = [
        'Kid' => $kid,
        'PrimaryParent' => $primaryParent,
        'SecondaryParents' => $secondaryParents,
        'Siblings' => $siblings
    ];

    return response()->json([
        'status' => 200,
        'data' => $response
    ], 200);
}
    
    

    public function getSubscriberDetails($id) {
        $sub_login = subscriberlogins::find($id);

        if ($sub_login) {
            $fetchingEntryId = $sub_login->id;
            $mainsubscriberfromref = $sub_login->MainSubscriberId;
            $subscriberDetails = $this->getMainSubscriber($mainsubscriberfromref);
            $regCodes = RegCodes::where('user_id', $fetchingEntryId)->get();

            if ($subscriberDetails->count() > 1) {
                if ($regCodes->isNotEmpty()) {
                    foreach ($regCodes as $regCode) {
                        $checkingCodeType = $regCode->code_type_id;

                        if ($checkingCodeType == 2) {
                            $mainId = $regCode->id;
                            $mainSubscriberId = $regCode->user_id;

                            $gettingMainsubId = subscriberlogins::where('id', $mainSubscriberId)->select('id', 'MainSubscriberId')->first();
                            $forfindingkid = $gettingMainsubId->MainSubscriberId;

                            if ($forfindingkid === 1) {
                                $forfindid = subscribersKidModel::where('MainSubscriberId', $mainSubscriberId)->get();
                            } else {
                                $forfindid = subscribersKidModel::where('MainSubscriberId', $forfindingkid)->get();
                            }

                            $userDetails = subscriberlogins::where('Ref_Inv_By', $mainId)->get();
                            $secondaryData = $userDetails->count() >= 1 ? $userDetails : "There are no secondary users";

                            return [
                                'status' => 200,
                                'loginUserData' => $sub_login,
                                'SecondaryData' => $secondaryData,
                                'MainSubsciberData' => $subscriberDetails,
                                'KidData' => $forfindid,
                            ];
                        }
                    }

                    return [
                        'status' => 202,
                        'message' => "No Secondary Data Available"
                    ];
                } else {
                    return [
                        'status' => 404,
                        'message' => "RegCode not found."
                    ];
                }
            }
        } else {
            return [
                'status' => 404,
                'message' => "Requested ID data not found."
            ];
        }
    }

    private function getMainSubscriber($id) {
        return subscriberlogins::find($id);
    }
}