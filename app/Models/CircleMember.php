<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CircleMember extends Model
{
    use HasFactory;

    protected $table = 'circle_members';

    protected $fillable = [
        'senderId',
        'receiverId',
        'profileType',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function sender()
    {
        return $this->belongsTo(SubscriberLogins::class, 'senderId');
    }

    public function receiver()
    {
        return $this->belongsTo(SubscriberLogins::class, 'receiverId');
    }
}


?>