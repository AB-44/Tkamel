<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'association_id', 'title', 'body',
        'type', 'is_read', 'related_id', 'related_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    /**
     * Polymorphic: related entity (Opportunity, Meeting, etc.)
     */
    public function related()
    {
        return $this->morphTo();
    }
}
