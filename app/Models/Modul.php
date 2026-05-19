<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $fillable = [
        'kelas_id',
        'original_name',
        'file_path',
        'file_size'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
