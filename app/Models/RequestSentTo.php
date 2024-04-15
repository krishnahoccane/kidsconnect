<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestSentTo extends Model
{
    use HasFactory;
    
    protected $table = 'request_sent_to';

    protected $fillable = [
        'RequestId',
        'RequestFromId',
        'RequestToId',
        'Receiverstatus',
        'ReceiverStatusDate',
        'ReceiverFeedBack',
        'RecFeedbackDate',
        'SenderFeedBack',
        'SenderFeedBackDate',
        'StatusId'

    ];

}
