<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    protected $fillable = [
        'husband_id',
        'wife_id',
        'married_at',
        'divorced_at'
    ];

    public function husband()
    {
        return $this->belongsTo(FamilyMember::class, 'husband_id');
    }

    public function wife()
    {
        return $this->belongsTo(FamilyMember::class, 'wife_id');
    }
}
