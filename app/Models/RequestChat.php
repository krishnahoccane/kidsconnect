<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestChat extends Model
{
    use HasFactory;

    protected $table = "request_chat";

    protected $fillable = [
        'RequestId',
        'RequestsentId',
        'ChatSenderId',
        'Message',
        'Attachment',

    ];
}
