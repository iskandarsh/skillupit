<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_session_id',
        'user_id',
        'is_present',
    ];

    protected $casts = [
        'is_present' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // Attendance milik session
    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'class_session_id');
    }

    // Attendance milik user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
