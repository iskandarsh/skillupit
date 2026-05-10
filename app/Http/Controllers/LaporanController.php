<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mentor;
use App\Models\Order;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index', [
            'totalMentor' => Mentor::count(),
            'totalKelas' => Kelas::count(),
            // Total lulus
            'totalLulus' => Order::where('status_kelas', 1)->count(),

            // Total referral yang terpakai
            'totalReferral' => Order::whereNotNull('referral_kode')
                ->where('referral_kode', '!=', '')
                ->count(),

            // 'totalLulus' => User::where('status', 'lulus')->count(),
        ]);
    }
}
