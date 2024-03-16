<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsCirclesMember extends Model 
{
    use HasFactory;

    protected $table = "sub_circles_members";

    protected $fillable = [
        'Subscirclesid',
        'Subscribedid',
        'Contactid'
        
    ];
}
