<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'main_speaker',
        'description',
        'date_time',
        'meeting_type',
        'direction',
        'category',
        'presenter',
        'date',
        'time',
        'type',
        'status',
        'link',
        'location',
        'location_url',
        'notes',
        'duration_minutes',
        'invitation_direction',
        'report_summary',
        'report_decisions',
        'report_attendees',
        'report_actions',
        'cancel_reason',
    ];

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
}
