<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'title',
        'date',
        'is_last_session',
        'link',
        'start_time',
        'end_time',
        // TAMBAHAN
        'record_video'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // Schedule milik 1 kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Schedule punya banyak session
    public function sessions()
    {
        return $this->hasMany(ClassSession::class, 'schedule_id')
            ->orderBy('session_order');
    }
}
