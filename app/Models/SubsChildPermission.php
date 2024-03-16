<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubsChildPermission extends Model
{
    use HasFactory;
    
    protected $table = 'sub_child_permissions';

    protected $fillable = [
        'Subschildid',
        'SubscriberId',
        'Permissionname',
        'Statusid',
    ];

}
