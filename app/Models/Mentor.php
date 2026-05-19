<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'photo',
        'job_title',
        'bio',
        'instagram_url',
        'linkedin_url',
    ];

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mentor')
            ->withTimestamps();
    }
}
