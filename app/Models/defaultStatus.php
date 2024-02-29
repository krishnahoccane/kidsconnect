<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class defaultStatus extends Model
{
    use HasFactory;

    // Here we are using the tale name which is created by using migrations
    protected $table = 'status';

    protected $fillable = [
        'name'
    ];
}
