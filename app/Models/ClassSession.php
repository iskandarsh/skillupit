<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSession extends Model
{
    use HasFactory;

    protected $table = 'class_sessions';

    protected $fillable = [
        'schedule_id',
        'title',
        'session_order',
        'is_end',
    ];

    protected $casts = [
        'is_end' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // Session milik schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Session punya banyak attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_session_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    // Cek apakah user sudah absen di session ini
    public function isUserPresent($userId)
    {
        return $this->attendances()
            ->where('user_id', $userId)
            ->where('is_present', true)
            ->exists();
    }
}
