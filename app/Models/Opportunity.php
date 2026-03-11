<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $fillable = [
        'created_by', 'title', 'direction', 'type',
        'budget', 'description', 'deadline', 'requirements',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function targets()
    {
        return $this->hasMany(OpportunityTarget::class);
    }

    public function targetAssociations()
    {
        return $this->belongsToMany(Association::class, 'opportunity_targets');
    }

    public function requests()
    {
        return $this->hasMany(OpportunityRequest::class);
    }
}
