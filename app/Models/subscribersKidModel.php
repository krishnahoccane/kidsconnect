<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscribersKidModel extends Model
{
    use HasFactory;

    protected $table='subscribers_kids';

    protected $fillable=[
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

    // public function eventRequests()
    // {
    //     return $this->hasMany(RequestModel::class, 'SubscribersKidId');
    // }
}
