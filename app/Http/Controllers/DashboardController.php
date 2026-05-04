<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Order;
use App\Models\Referral;
use App\Models\Schedule;
use App\Models\Sertifikat;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $user = auth()->user();

    //     // =========================
    //     // 🔄 SYNC STATUS DARI XENDIT
    //     // =========================
    //     $pendingOrders = Order::where('email', $user->email)
    //         ->where('status', 'pending')
    //         ->whereNotNull('invoice_id')
    //         ->get();

    //     foreach ($pendingOrders as $order) {

    //         try {
    //             $response = \Illuminate\Support\Facades\Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
    //                 ->get('https://api.xendit.co/v2/invoices/' . $order->invoice_id);

    //             if ($response->successful()) {
    //                 $invoice = $response->json();

    //                 // 🔥 STATUS DARI XENDIT
    //                 if ($invoice['status'] === 'PAID') {
    //                     $order->update([
    //                         'status' => 'paid'
    //                     ]);
    //                 } elseif ($invoice['status'] === 'EXPIRED') {
    //                     $order->update([
    //                         'status' => 'expired'
    //                     ]);
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             // biar gak crash
    //         }
    //     }

    //     // =========================
    //     // 📚 MY KELAS (ONLY PAID)
    //     // =========================
    //     $myKelas = Kelas::with(['moduls', 'orders'])
    //         ->whereHas('orders', function ($query) use ($user) {
    //             $query->where('email', $user->email)
    //                 ->where('status', 'paid');
    //         })
    //         ->get();

    //     $totalKelas = $myKelas->count();

    //     // =========================
    //     // 📊 ABSENSI (DUMMY)
    //     // =========================
    //     $hadir = 5;
    //     $tidakHadir = 2;

    //     $totalAbsensi = $hadir + $tidakHadir;

    //     $persentase = $totalAbsensi > 0
    //         ? round(($hadir / $totalAbsensi) * 100)
    //         : 0;

    //     // =========================
    //     // 📅 JADWAL HARI INI (DUMMY)
    //     // =========================
    //     $jadwalHariIni = collect([
    //         (object)[
    //             'id' => 1,
    //             'kelas' => (object)['nama_kelas' => 'Laravel Basic'],
    //             'mentor' => (object)['name' => 'Mentor (Dummy)'],
    //             'jam_mulai' => '09:00',
    //             'jam_selesai' => '11:00',
    //             'absen_status' => null,
    //         ],
    //         (object)[
    //             'id' => 2,
    //             'kelas' => (object)['nama_kelas' => 'Vue JS'],
    //             'mentor' => (object)['name' => 'Mentor (Dummy)'],
    //             'jam_mulai' => '13:00',
    //             'jam_selesai' => '15:00',
    //             'absen_status' => 'hadir',
    //         ],
    //     ]);

    //     // =========================
    //     // 📅 JADWAL MENDATANG (DUMMY)
    //     // =========================
    //     $jadwalMendatang = collect([
    //         (object)[
    //             'id' => 3,
    //             'kelas' => (object)['nama_kelas' => 'React JS'],
    //             'tanggal' => '2026-05-05',
    //             'jam_mulai' => '10:00',
    //             'jam_selesai' => '12:00',
    //         ],
    //         (object)[
    //             'id' => 4,
    //             'kelas' => (object)['nama_kelas' => 'UI/UX Design'],
    //             'tanggal' => '2026-05-06',
    //             'jam_mulai' => '13:00',
    //             'jam_selesai' => '15:00',
    //         ],
    //     ]);

    //     return view('dashboard', compact(
    //         'totalKelas',
    //         'hadir',
    //         'tidakHadir',
    //         'persentase',
    //         'jadwalHariIni',
    //         'jadwalMendatang',
    //         'myKelas'
    //     ));
    // }

    public function index()
    {
        $user = auth()->user();

        // =========================
        // SYNC XENDIT (tetap)
        // =========================
        $pendingOrders = Order::where('email', $user->email)
            ->where('status', 'pending')
            ->whereNotNull('invoice_id')
            ->get();

        foreach ($pendingOrders as $order) {
            try {
                $response = \Illuminate\Support\Facades\Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
                    ->get('https://api.xendit.co/v2/invoices/' . $order->invoice_id);

                if ($response->successful()) {
                    $invoice = $response->json();

                    if ($invoice['status'] === 'PAID') {
                        $order->update(['status' => 'paid']);
                    } elseif ($invoice['status'] === 'EXPIRED') {
                        $order->update(['status' => 'expired']);
                    }
                }
            } catch (\Exception $e) {
            }
        }

        // =========================
        // KELAS USER
        // =========================
        $myKelas = Kelas::with(['moduls', 'orders', 'schedules.sessions', 'mentors'])
            ->whereHas('orders', function ($q) use ($user) {
                $q->where('email', $user->email)
                    ->where('status', 'paid');
            })
            ->get();


        $totalKelas = $myKelas->count();


        // =========================
        // 🎟️ VOUCHER USER
        // =========================
        $referrals = Referral::with(['kelas', 'user'])
            ->where(function ($q) use ($user) {
                // voucher khusus user ini
                $q->where('user_id', $user->id)
                    // atau voucher global (untuk semua user)
                    ->orWhereNull('user_id');
            })
            ->get();
        // =========================
        // ABSENSI REAL
        // =========================
        $hadir = DB::table('attendances')
            ->where('user_id', $user->id)
            ->where('is_present', 1)
            ->count();

        $tidakHadir = DB::table('attendances')
            ->where('user_id', $user->id)
            ->where('is_present', 0)
            ->count();

        $totalAbsensi = $hadir + $tidakHadir;

        $persentase = $totalAbsensi > 0
            ? round(($hadir / $totalAbsensi) * 100)
            : 0;

        // =========================
        // SCHEDULE REAL
        // =========================
        $today = now()->toDateString();

        $schedules = Schedule::with(['kelas', 'sessions'])
            ->whereIn('kelas_id', $myKelas->pluck('id'))
            ->orderBy('date')
            ->get();

        $jadwalHariIni = collect();
        $jadwalMendatang = collect();

        $seenSessionOrders = []; // 🔥 penting untuk dedup

        foreach ($schedules as $schedule) {

            $session = $schedule->sessions->sortBy('session_order')->first();
            if (!$session) continue;

            $sessionOrder = $session->session_order;

            // =========================
            // 🔥 CEK ATTENDANCE
            // =========================
            $attendance = DB::table('attendances')
                ->where('class_session_id', $session->id)
                ->where('user_id', $user->id)
                ->first();

            // =========================
            // 🔥 RULE DUPLICATE FIX
            // =========================
            if (isset($seenSessionOrders[$sessionOrder])) {

                // kalau sudah ada sebelumnya TANPA attendance
                // tapi sekarang ADA attendance → replace
                if ($attendance && !$seenSessionOrders[$sessionOrder]['has_attendance']) {
                    $seenSessionOrders[$sessionOrder] = [
                        'schedule' => $schedule,
                        'attendance' => $attendance
                    ];
                }

                continue;
            }

            // simpan pertama kali
            $seenSessionOrders[$sessionOrder] = [
                'schedule' => $schedule,
                'attendance' => $attendance
            ];
        }

        // =========================
        // ambil hasil final
        // =========================
        foreach ($seenSessionOrders as $data) {

            $schedule = $data['schedule'];
            $attendance = $data['attendance'];

            $absen_status = null;

            if ($attendance && $attendance->is_present == 1) {
                $absen_status = 'hadir';
            } elseif ($attendance && $attendance->is_present == 0) {
                $absen_status = 'tidak';
            }

            $item = (object)[
                'id' => $schedule->id,
                'kelas' => $schedule->kelas,
                'mentor' => (object)['name' => 'Mentor'],
                'date' => $schedule->date,
                'jam_mulai' => $schedule->start_time,
                'jam_selesai' => $schedule->end_time,
                'absen_status' => $absen_status,
                'can_join' => $this->canJoinSchedule($schedule, $user->id),
            ];

            if ($schedule->date == now()->toDateString()) {
                $jadwalHariIni->push($item);
            } elseif ($schedule->date > now()->toDateString()) {
                $jadwalMendatang->push($item);
            }
        }



        // =========================
        // 🏆 SERTIFIKAT USER
        // =========================
        $certificates = Sertifikat::with(['kelas', 'order']) // Load relasi kelas dan order
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard', compact(
            'totalKelas',
            'hadir',
            'tidakHadir',
            'persentase',
            'jadwalHariIni',
            'jadwalMendatang',
            'myKelas',
            'referrals',
            'certificates'
        ));
    }


    private function canJoinSchedule(Schedule $schedule, int $userId): bool
    {
        // ✅ FIX UTAMA: user baru selalu boleh join
        $hasAnyAttendance = DB::table('attendances')
            ->where('user_id', $userId)
            ->exists();

        if (!$hasAnyAttendance) {
            return true;
        }

        $currentSession = $this->getCurrentSession($schedule);
        if (!$currentSession) return true;

        $session1 = $schedule->sessions()
            ->orderBy('session_order', 'asc')
            ->first();

        if ($session1) {
            $alreadyPresentSession1 = DB::table('attendances')
                ->where('class_session_id', $session1->id)
                ->where('user_id', $userId)
                ->where('is_present', 1)
                ->exists();

            if ($alreadyPresentSession1) {
                return true;
            }
        }

        $previousSchedule = $this->getPreviousSchedule($schedule);

        if (!$previousSchedule) return true;

        $lastSession = $previousSchedule->sessions()
            ->orderBy('session_order', 'desc')
            ->first();

        if (!$lastSession) return true;

        return DB::table('attendances')
            ->where('class_session_id', $lastSession->id)
            ->where('user_id', $userId)
            ->where('is_present', 1)
            ->exists();
    }

    private function getCurrentSession(Schedule $schedule)
    {
        return $schedule->sessions()->orderBy('session_order', 'asc')->first();
    }


    private function getPreviousSchedule(Schedule $schedule)
    {
        return Schedule::where('kelas_id', $schedule->kelas_id)
            ->where(function ($q) use ($schedule) {
                $q->where('date', '<', $schedule->date)
                    ->orWhere(function ($q) use ($schedule) {
                        $q->where('date', $schedule->date)
                            ->where('start_time', '<', $schedule->start_time);
                    });
            })
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->first();
    }
}
