<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'created_by', 'category_id', 'title', 'main_speaker', 'description',
        'date_time', 'end_date_time', 'meeting_type', 'direction', 'link', 'location',
        'status', 'cancel_reason', 'report_summary', 'report_decisions',
        'report_attendees', 'report_actions', 'category', 'presenter', 'date',
        'time', 'type', 'location_url', 'notes', 'duration_minutes', 'invitation_direction'
    ];

    public function category()
    {
        return $this->belongsTo(AssociationCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function targets()
    {
        return $this->hasMany(MeetingTarget::class);
    }

    public function targetAssociations()
    {
        return $this->belongsToMany(Association::class, 'meeting_targets');
    }

    public function agendaItems()
    {
        return $this->hasMany(MeetingAgendaItem::class)->orderBy('order_index');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'meeting_user');
    }

    public function attendeeAssociations()
    {
        return $this->belongsToMany(Association::class, 'meeting_association')->withTimestamps();
    }
}
