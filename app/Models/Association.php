<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
    protected $fillable = [
        'association_name', 'email', 'license_number',
        'category', 'manager_name', 'phone', 'password_hash',
        'status', 'admin_notes', 'reviewed_at',
    ];

    protected $hidden = ['password_hash'];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }

    public function serviceRequests()     { return $this->hasMany(ServiceRequest::class); }
    public function opportunityTargets()  { return $this->hasMany(OpportunityTarget::class); }
    public function opportunities()       { return $this->belongsToMany(Opportunity::class, 'opportunity_targets'); }
    public function opportunityRequests() { return $this->hasMany(OpportunityRequest::class); }
    public function meetingTargets()      { return $this->hasMany(MeetingTarget::class); }
    public function meetings()            { return $this->belongsToMany(Meeting::class, 'meeting_targets'); }
    public function receivedMessages()    { return $this->morphMany(Message::class, 'receiver'); }
    public function notifications()       { return $this->hasMany(Notification::class); }
}
