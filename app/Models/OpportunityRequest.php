<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityRequest extends Model
{
    protected $fillable = ['opportunity_id', 'project_id', 'association_id', 'user_id', 'status', 'notes'];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function project()
    {
        return $this->belongsTo(JointProject::class, 'project_id');
    }

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
