<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'referrals'; // atau hapus juga boleh (default sudah benar)

    protected $fillable = [
        'kode',
        'user_id',
        'kelas_id',
        'disc' // ✅ tambah ini
    ];

    protected $casts = [
        'user_id' => 'integer',
        'kelas_id' => 'integer',
        'disc' => 'integer'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER (IMPORTANT 🔥)
    |--------------------------------------------------------------------------
    */

    public function isAllUser()
    {
        return is_null($this->user_id);
    }

    public function isAllKelas()
    {
        return is_null($this->kelas_id);
    }
}
