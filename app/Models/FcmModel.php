<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcmModel extends Model
{
    use HasFactory;

    protected $table = 'subscriber_fcm_token'; // Make sure this matches your table name

    protected $fillable = [
        'subscriberId',
        'deviceId',
        'fcm_token',
    ];
}
