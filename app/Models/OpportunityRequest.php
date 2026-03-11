<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityRequest extends Model
{
    protected $fillable = ['opportunity_id', 'association_id', 'status', 'notes'];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function association()
    {
        return $this->belongsTo(Association::class);
    }
}
