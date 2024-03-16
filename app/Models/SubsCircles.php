<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsCircles extends Model
{
    use HasFactory;
    protected $table = "subs_contact";

    protected $fillable = [
        'subscribeId' 
    ];

}
