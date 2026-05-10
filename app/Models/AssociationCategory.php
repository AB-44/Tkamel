<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociationCategory extends Model
{
    protected $fillable = ['name', 'icon', 'color', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    /** All associations in this category */
    public function associations()
    {
        return $this->hasMany(Association::class, 'category', 'name');
    }

    /** Count of approved associations in this category */
    public function approvedAssociationsCount(): int
    {
        return Association::where('category', $this->name)
            ->where('status', 'approved')
            ->count();
    }
}
