<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_user_id', 'receiver_type', 'receiver_id',
        'content', 'is_read',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    /**
     * Polymorphic: receiver can be User or Association
     */
    public function receiver()
    {
        return $this->morphTo();
    }
}
