<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subScriberContactModel extends Model
{
    use HasFactory;
    protected $table = 'subscriber_contacts';

    protected $fillable = [
        'subscriberId',
        'contactedId',
        'status'
    ];
}
