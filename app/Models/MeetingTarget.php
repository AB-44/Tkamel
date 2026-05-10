<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingTarget extends Model
{
    protected $fillable = ['meeting_id', 'association_id'];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function association()
    {
        return $this->belongsTo(Association::class);
    }
}
