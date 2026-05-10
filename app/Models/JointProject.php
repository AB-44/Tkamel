<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JointProject extends Model
{
    protected $fillable = [
        'name', 'category_id', 'description',
        'status', 'progress', 'start_date', 'end_date', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'progress'   => 'integer',
    ];

    /** التصنيف */
    public function category()
    {
        return $this->belongsTo(AssociationCategory::class, 'category_id');
    }

    /** سجل التقدمات */
    public function updates()
    {
        return $this->hasMany(JointProjectUpdate::class, 'project_id')->latest();
    }

    /** منشئ المشروع */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
