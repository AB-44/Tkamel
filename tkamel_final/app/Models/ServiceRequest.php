<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'association_id', 'handled_by', 'service_type',
        'title', 'details', 'budget', 'preferred_date', 'status',
    ];

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
