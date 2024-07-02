<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscribersKidModel extends Model
{
    use HasFactory;

    protected $table='subscribers_kids';

    protected $fillable=[
        'chatId',
        'MainSubscriberId',
        'RoleId',
        'FirstName',
        'LastName',
        'Email',
        'Dob',
        'Gender',
        'PhoneNumber',
        'SSN',
        'Password',
        'LoginType',
        'About',
        'Address',
        'City',
        'State',
        'ProfileImage',
        'Keywords',
        'ProfileStatus',
        'IsApproved',
        'AccessApprovedOn',
        'AccessApprovedBy'
    ];

    
}
