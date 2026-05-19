<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // WAJIB ADA: Daftarkan semua kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'nama_kelas',
        'deskripsi',
        'harga',
        'harga_coret',
        'thumbnail', // <-- Pastikan ini ada!
        'is_active',   // status aktif / tidak
        'periode',      // periode kelas (misal: "Juni 2026" atau "Batch 1")
        'kategori', // 👈 tambahkan ini
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function moduls()
    {
        return $this->hasMany(Modul::class);
    }

    public function mentors()
    {
        return $this->belongsToMany(Mentor::class, 'kelas_mentor')
            ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'kelas_id');
    }
}
