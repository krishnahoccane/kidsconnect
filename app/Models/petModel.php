<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class petModel extends Model
{
    use HasFactory;

    protected $table='pets';

    protected $fillable=[
        'MainSubscriberId',
        'RoleId',
        'Name',
        'Breed',
        'Dob'
        
    ];
}
