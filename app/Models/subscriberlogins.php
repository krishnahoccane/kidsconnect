<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

// use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;

class subscriberlogins extends AuthenticatableUser
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $guard = 'subscriber_logins';

    protected $table = 'subscriber_logins';
    protected $primaryKey = "id";

    protected $fillable = [
        'DeviceId',
        'FirstName',
        'LastName',
        'Email',
        'Password',
        'BirthYear',
        'Gender',
        'PhoneNumber',
        'IsMain',
        'EntryCode',
        'Ref_Inv_By',
        'LoginType',
        'About',
        'Address',
        'City',
        'State',
        'Zipcode',
        'Country',
        'ProfileImage',
        'Keywords',
        'IsApproved',
        'ApprovedOn',
        'ApprovedBy',
        'ProfileStatus',
        'RoleId',
        'MainSubscriberId',
    ];

    
}



