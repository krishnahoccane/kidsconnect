<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;

class subscriberlogins extends AuthenticatableUser implements Authenticatable
{
    use HasFactory, HasApiTokens;
    
    protected $table = "subscriber_logins";

    protected $fillable = [
        'FirstName',
        'LastName',
        'Email',
        'Dob',
        'Gender',
        'PhoneNumber',
        'SSN',
        'Password',
        'About',
        'Address',
        'ProfileImage',
        'SSNimage',
        'IsApproved',
        'ApprovedOn',
        'ApprovedBy',
        'ProfileStatus',
        'Keywords',
        'LoginType',
        'IsMain',
        'RoleId',
        'MainSubscriberId',
    ];
}

