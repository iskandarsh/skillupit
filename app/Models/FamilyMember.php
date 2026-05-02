<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = [
        'name',
        'role',
        'photo',
        'gender',
        'parent_id',
        'is_dead',
        'death_date'
    ];

    public function children()
    {
        return $this->hasMany(FamilyMember::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(FamilyMember::class, 'parent_id');
    }

    public function marriagesAsHusband()
    {
        return $this->hasMany(Marriage::class, 'husband_id');
    }

    public function marriagesAsWife()
    {
        return $this->hasMany(Marriage::class, 'wife_id');
    }
}
