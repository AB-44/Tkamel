<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JointProjectUpdate extends Model
{
    protected $fillable = ['project_id', 'body'];

    public function project()
    {
        return $this->belongsTo(JointProject::class, 'project_id');
    }
}
