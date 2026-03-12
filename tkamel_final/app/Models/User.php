<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'role_id', 'full_name', 'email', 'password_hash',
    ];

    protected $hidden = ['password_hash'];

    /**
     * Tell Laravel Auth to use password_hash instead of password.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    /**
     * The column used for authentication credential lookup.
     */
    public function getAuthIdentifierName(): string
    {
        return 'email';
    }

    // Relations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'created_by');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'created_by');
    }

    public function handledServiceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'handled_by');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_user_id');
    }

    public function receivedMessages()
    {
        return $this->morphMany(Message::class, 'receiver');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
