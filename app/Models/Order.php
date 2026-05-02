<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'kelas_id',
        'nama_kelas',
        'amount',
        'status',
        'invoice_id',
        'payment_url',
        'status_kelas',
        'referral_kode',
        'disc'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
