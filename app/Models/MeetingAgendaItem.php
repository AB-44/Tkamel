<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingAgendaItem extends Model
{
    protected $fillable = [
        'meeting_id', 'topic_title', 'presenter_name',
        'duration_minutes', 'order_index',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
