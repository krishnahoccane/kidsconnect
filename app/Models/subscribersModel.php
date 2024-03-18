<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscribersModel extends Model
{
    use HasFactory;

    protected $table = "subscribers";

    protected $fillable = [
        'RoleId',
        'Email',
        'ProfileStatus',
        'ApprovedOn',
        'ApprovedBy',
        'DeniedOn',
        'DeniedBy'
    ];
}
