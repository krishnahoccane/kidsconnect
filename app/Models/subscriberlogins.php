<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscriberlogins extends Model
{
    use HasFactory;

    protected $table = "subscriber_logins";

    protected $fillable = [
        'FirstName',
        'LastName',
        'email',
        'Dob',
        'Gender',
        'PhoneNumber',
        'SSN',
        'Password',
        'About',
        'Address',
        'ProfileImage',
        // 'Keywords',
    ];
}
