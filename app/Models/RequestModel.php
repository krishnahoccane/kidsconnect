<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    protected $table = 'request';
    protected $fillable = [
        'SubscriberId',
        'SubschildId',
        'Subject' ,
        'RequestFor',
        'EventFrom',
        'EventTo',
        'Keywords',
        'RecordType	',
        'Statusid',
        'LocationType',
        'Location',
        'PickDropInfo',
        'SpecialNotes',
        'PrimaryResponsibleId',
        'ActivityType',
        'areGroupMemberVisible',
        'IsGroupChat',
        'CreatedBy',
        'UpdatedBy',


    ];
}
