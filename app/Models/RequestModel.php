<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    protected $table = 'EventRequests';
    protected $primaryKey = "id";
    protected $fillable = [
        'SubscriberId',
        'SubscribersKidId',
        'EventName',
        'EventType',
        'EventFor',
        'EventStartDate',
        'EventEndDate',
        'EventStartTime',
        'EventEndTime',
        'Keywords',
        'RecordType',
        'Statusid',
        'EventLocation',
        'EventInfo',
        'LocationType',
        'EventLocation',
        'EventInfo', 
        'PickupLocation',
        'DropLocation',
        'IsPickUp',
        'PrimaryResponsibleId',
        'ActivityType',
        'areGroupMemberVisible',
        'IsGroupChat',
        'CreatedBy',
        'UpdatedBy',


    ];
}
