<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Registration extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = "registration";
    protected $primaryKey = "id";
}
