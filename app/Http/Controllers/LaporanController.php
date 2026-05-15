<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mentor;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $totalMentor = Mentor::count();

        $totalKelas = Kelas::count();

        $totalLulus = Order::where('status_kelas', 1)->count();

        $totalReferral = Order::whereNotNull('referral_kode')
            ->where('referral_kode', '!=', '')
            ->count();

        /*
    |--------------------------------------------------------------------------
    | Rate Kelulusan
    |--------------------------------------------------------------------------
    */

        $totalOrder = Order::count();

        $rateLulus = $totalOrder > 0
            ? round(($totalLulus / $totalOrder) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | LAPORAN KEUANGAN
        |--------------------------------------------------------------------------
        */

        $totalRevenue = Order::where('status', 'PAID')
            ->sum('amount');

        $totalDiscount = Order::where('status', 'PAID')
            ->sum('disc');

        $netRevenue = $totalRevenue - $totalDiscount;

        $totalPaid = Order::where('status', 'PAID')->count();

        $totalPending = Order::where('status', 'PENDING')->count();

        return view('laporan.index', compact(
            'totalMentor',
            'totalKelas',
            'totalLulus',
            'totalReferral',
            'rateLulus',
            'totalRevenue',
            'totalDiscount',
            'netRevenue',
            'totalPaid',
            'totalPending'
        ));
    }

    public function chart()
    {
        /*
        |--------------------------------------------------------------------------
        | Popularitas Kelas
        |--------------------------------------------------------------------------
        */

        $kelasPopuler = Order::select(
            'nama_kelas',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('nama_kelas')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Efikasi Belajar
        |--------------------------------------------------------------------------
        */

        $lulus = Order::where('status_kelas', 1)->count();

        $progress = Order::where('status_kelas', 0)->count();

        $lainnya = Order::whereNull('status_kelas')->count();

        /*
        |--------------------------------------------------------------------------
        | Revenue Per Kelas
        |--------------------------------------------------------------------------
        */

        $revenueKelas = Order::select(
            'nama_kelas',
            DB::raw('SUM(amount - COALESCE(disc,0)) as total_revenue')
        )
            ->where('status', 'PAID')
            ->groupBy('nama_kelas')
            ->orderByDesc('total_revenue')
            ->get();

        return response()->json([
            'kelasLabels' => $kelasPopuler->pluck('nama_kelas'),
            'kelasTotals' => $kelasPopuler->pluck('total'),
            'revenueLabels' => $revenueKelas->pluck('nama_kelas'),

            'revenueTotals' => $revenueKelas->pluck('total_revenue'),
            'statusLabels' => [
                'Lulus',
                'On Progress',
                'Lainnya'
            ],

            'statusTotals' => [
                $lulus,
                $progress,
                $lainnya
            ]
        ]);
    }
}
