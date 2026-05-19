<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_session_id',
        'title',
        'description',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // Assignment milik session
    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'class_session_id');
    }

    // Assignment punya banyak submission
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    // Cek deadline sudah lewat atau belum
    public function isExpired()
    {
        return $this->deadline && now()->gt($this->deadline);
    }

    // Cek apakah user sudah submit
    public function hasSubmitted($userId)
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->exists();
    }

    // Ambil submission user tertentu
    public function userSubmission($userId)
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->first();
    }
}
