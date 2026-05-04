<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function store(Request $request, $scheduleId)
    {
        $user = auth()->user();

        $schedule = Schedule::with('sessions')->findOrFail($scheduleId);

        // ambil session pertama (atau current session)
        $session = $schedule->sessions->sortBy('session_order')->first();

        if (!$session) {
            return back()->with('error', 'Session tidak ditemukan');
        }

        // =========================
        // RULE: tidak boleh skip sesi sebelumnya
        // =========================
        $canJoin = $this->canJoinSchedule($schedule, $user->id);

        if (!$canJoin) {
            return back()->with('error', 'Kamu belum menyelesaikan sesi sebelumnya');
        }

        // =========================
        // CEK SUDAH ABSEN
        // =========================
        $already = DB::table('attendances')
            ->where('user_id', $user->id)
            ->where('class_session_id', $session->id)
            ->first();

        if ($already) {
            return back()->with('info', 'Kamu sudah absen di sesi ini');
        }

        $activeOrder = \App\Models\Order::where('email', $user->email)
            ->where('kelas_id', $schedule->kelas_id)
            ->where('status_kelas', 0)
            ->latest()
            ->first();

        // =========================
        // SIMPAN ABSENSI
        // =========================
        DB::table('attendances')->insert([
            'user_id' => $user->id,
            'class_session_id' => $session->id,
            'order_id' => $activeOrder?->id, // 🔥 TAMBAHAN INI
            'is_present' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // =========================
        // 🔥 CEK LAST SESSION
        // =========================
        if ((int) $session->is_end === 1) {

            $referral = Referral::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'kelas_id' => $schedule->kelas_id,
                ],
                [
                    'kode' => strtoupper('REF-' . Str::random(6)),
                    'disc' => 10
                ]
            );

            DB::table('sertifikat')->insert([
                'user_id' => $user->id,
                'kelas_id' => $schedule->kelas_id,
                'order_id' => $activeOrder?->id,
                'no' => strtoupper('CERT-' . Str::random(8)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Absensi berhasil!');
    }


    private function isLastSession(Schedule $schedule, $currentSession): bool
    {
        $lastSession = $schedule->sessions()
            ->orderBy('session_order', 'desc')
            ->first();

        if (!$lastSession) return false;

        return $lastSession->id === $currentSession->id;
    }

    // private function canJoinSchedule(Schedule $schedule, int $userId): bool
    // {
    //     // SESSION 1 selalu boleh
    //     $currentSession = $this->getCurrentSession($schedule);
    //     if (!$currentSession) return true;

    //     if ((int)$currentSession->session_order === 1) {
    //         return true;
    //     }

    //     // cari schedule sebelumnya
    //     $previousSchedule = $this->getPreviousSchedule($schedule);

    //     if (!$previousSchedule) return true;

    //     // ambil session terakhir dari schedule sebelumnya
    //     $lastSession = $previousSchedule->sessions()
    //         ->orderBy('session_order', 'desc')
    //         ->first();

    //     if (!$lastSession) return true;

    //     // WAJIB SUDAH PRESENT DI SESSION SEBELUMNYA
    //     return DB::table('attendances')
    //         ->where('class_session_id', $lastSession->id)
    //         ->where('user_id', $userId)
    //         ->where('is_present', 1)
    //         ->exists();
    // }

    // private function canJoinSchedule(Schedule $schedule, int $userId): bool
    // {
    //     $currentSession = $this->getCurrentSession($schedule);
    //     if (!$currentSession) return true;

    //     // =========================
    //     // 🔥 CEK SESSION 1 SUDAH HADIR
    //     // =========================
    //     $session1 = $schedule->sessions()
    //         ->orderBy('session_order', 'asc')
    //         ->first();

    //     if ($session1) {
    //         $alreadyPresentSession1 = DB::table('attendances')
    //             ->where('class_session_id', $session1->id)
    //             ->where('user_id', $userId)
    //             ->where('is_present', 1)
    //             ->exists();

    //         // kalau sudah hadir session 1 → bebas lanjut semua session
    //         if ($alreadyPresentSession1) {
    //             return true;
    //         }
    //     }

    //     // =========================
    //     // RULE LAMA (CHAIN VALIDATION)
    //     // =========================
    //     $previousSchedule = $this->getPreviousSchedule($schedule);

    //     if (!$previousSchedule) return true;

    //     $lastSession = $previousSchedule->sessions()
    //         ->orderBy('session_order', 'desc')
    //         ->first();

    //     if (!$lastSession) return true;

    //     return DB::table('attendances')
    //         ->where('class_session_id', $lastSession->id)
    //         ->where('user_id', $userId)
    //         ->where('is_present', 1)
    //         ->exists();
    // }

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
