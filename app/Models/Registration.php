<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class registration extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $guard = 'registration';
    protected $table = 'registration'; // Specify the correct table name
    protected $primaryKey = 'id'; // Specify the correct primary key column name
    protected $fillable = [
        'username',
        'email',
        'plain_password',
        'password', // Assuming you have a hashed password field named 'password'
    ];
}
