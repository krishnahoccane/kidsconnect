<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscriber extends Model
{
    use HasFactory;

    protected $table = 'subscribers';

    protected $fillable = [
        'typeid',
        'status',
        'isapproved',
        'approvedon',
        'approvedby'
    ];
}
