<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegCodes extends Model
{
    use HasFactory;

    protected $table = 'reg_codes';

    protected $fillable = [
        'code_type_id',
        'code_number',
        'user_id'
    ];
}
