<?php

namespace App\Services;

use App\Models\subscriberlogins;
use App\Models\RegCodes;
use App\Models\subscribersKidModel;

class SubscriberService {

    public function showKidParent($kidId)
    {
        $kid = subscribersKidModel::find($kidId);

        if (!$kid) {
            return [
                'status' => 404,
                'message' => 'Kid not found'
            ];
        }

        $primaryParent = subscriberlogins::where('id', $kid->MainSubscriberId)->first();
        $secondaryParents = subscriberlogins::where('MainSubscriberId', $kid->MainSubscriberId)
            ->where('id', '!=', $primaryParent->id)
            ->get();

        $response = [
            'Kid' => $kid,
            'PrimaryParent' => $primaryParent,
            'SecondaryParents' => $secondaryParents
        ];

        return [
            'status' => 200,
            'data' => $response
        ];
    }

    
    public function getSubscriberDetails($id)
    {
        $sub_login = subscriberlogins::find($id);

        if ($sub_login) {
            $fetchingEntryId = $sub_login->id;
            $mainsubscriberfromref = $sub_login->MainSubscriberId;
            $subscriberDetails = $this->getMainSubscriber($mainsubscriberfromref);

            if (!$subscriberDetails) {
                return [
                    'status' => 404,
                    'message' => "Main subscriber not found."
                ];
            }

            $regCodes = RegCodes::where('user_id', $fetchingEntryId)->get();

            if ($regCodes->isNotEmpty()) {
                foreach ($regCodes as $regCode) {
                    $checkingCodeType = $regCode->code_type_id;

                    if ($checkingCodeType == 2) {
                        $mainId = $regCode->id;
                        $mainSubscriberId = $regCode->user_id;

                        $gettingMainsubId = subscriberlogins::where('id', $mainSubscriberId)->select('id', 'MainSubscriberId')->first();

                        if (!$gettingMainsubId) {
                            return [
                                'status' => 404,
                                'message' => "Main subscriber ID not found."
                            ];
                        }

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
        } else {
            return [
                'status' => 404,
                'message' => "Requested ID data not found."
            ];
        }
    }

    private function getMainSubscriber($id)
    {
        $mainSubscriber = subscriberlogins::find($id);
        return $mainSubscriber ? $mainSubscriber : null;
    }
}
