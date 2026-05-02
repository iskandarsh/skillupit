<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Kelas;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();

        if ($request->ajax()) {
            return response()->json(
                Schedule::with('kelas', 'sessions')->latest()->get()
            );
        }

        return view('schedule', compact('kelas'));
    }

    public function show(Request $request, $id)
    {
        $schedule = Schedule::with('kelas', 'sessions')->findOrFail($id);
        $userId = $request->query('user_id');

        if ($userId && !$this->canJoinSchedule($schedule, (int)$userId)) {
            return response()->json([
                'message' => 'Kamu belum absen pada sesi sebelumnya, jadi belum bisa mengikuti schedule ini.'
            ], 403);
        }

        return response()->json($schedule);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'link' => 'nullable|url',
            'session_order' => 'required|integer|between:1,10',
            'is_last_session' => 'nullable|boolean',
        ]);

        $schedule = DB::transaction(function () use ($request) {
            $schedule = Schedule::create([
                'kelas_id' => $request->kelas_id,
                'title' => $request->title,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'link' => $request->link,
                'is_last_session' => $request->boolean('is_last_session'),
            ]);

            $schedule->sessions()->create([
                'title' => 'Session ' . $request->session_order,
                'session_order' => $request->session_order,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_end' => $request->boolean('is_last_session'),
            ]);

            return $schedule;
        });

        // Catatan: Memanggil email secara sinkronus di Controller bisa membuat loading lambat
        $this->sendAbsentReminderEmail($schedule);

        return response()->json(['message' => 'Schedule berhasil dibuat']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'link' => 'nullable|url',
            'session_order' => 'required|integer|between:1,10',
            'is_last_session' => 'nullable|boolean',
        ]);

        $schedule = Schedule::findOrFail($id);

        DB::transaction(function () use ($request, $schedule) {
            $schedule->update([
                'kelas_id' => $request->kelas_id,
                'title' => $request->title,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'link' => $request->link,
                'is_last_session' => $request->boolean('is_last_session'),
            ]);

            // FIX: Jangan di-delete! Kalau di-delete, semua data absen murid di sesi ini ikut terhapus atau orphan.
            // Gunakan updateOrCreate untuk menjaga integrity data absen.
            $schedule->sessions()->updateOrCreate(
                ['schedule_id' => $schedule->id],
                [
                    'title' => 'Session ' . $request->session_order,
                    'session_order' => $request->session_order,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'is_end' => $request->boolean('is_last_session'),
                ]
            );
        });

        $this->sendAbsentReminderEmail($schedule);

        return response()->json(['message' => 'Schedule berhasil diupdate']);
    }

    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();
        return response()->json(['message' => 'Schedule berhasil dihapus']);
    }

    // =========================
    // SESSION HELPERS
    // =========================
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

    // =========================
    // EMAIL REMINDER (FINAL LOGIC)
    // =========================
    private function sendAbsentReminderEmail(Schedule $schedule)
    {
        $session = $this->getCurrentSession($schedule);
        if (!$session) return;

        $participants = Order::where('kelas_id', $schedule->kelas_id)
            ->where('status', 'paid')
            ->get();

        $users = User::whereIn('email', $participants->pluck('email'))
            ->get()
            ->keyBy('email');

        $presentUsers = DB::table('attendances')
            ->where('class_session_id', $session->id)
            ->where('is_present', 1)
            ->pluck('user_id')
            ->toArray();

        foreach ($participants as $order) {

            $user = $users->get($order->email);
            if (!$user) continue;

            // ❌ sudah hadir → skip
            if (in_array($user->id, $presentUsers)) {
                continue;
            }

            // ❌ tidak lolos rule chain → skip
            if (!$this->canJoinSchedule($schedule, $user->id)) {
                continue;
            }

            // ✔ kirim email
            Mail::raw(
                "Halo {$order->nama},\n\n" .
                    "Sesi baru telah dimulai 🎓\n\n" .
                    "📚 Kelas: {$schedule->title}\n" .
                    "⏰ Waktu: {$schedule->start_time} - {$schedule->end_time}\n\n" .
                    "⚠️ Pastikan kamu sudah menyelesaikan sesi sebelumnya agar bisa melanjutkan pembelajaran.\n\n" .
                    "Silakan segera melakukan absensi di sesi ini agar progress kamu tercatat.\n\n" .
                    "🔗 Link : {$schedule->link}\n\n" .
                    "Tetap konsisten ya, biar tidak tertinggal materi 🚀",
                function ($message) use ($order, $schedule) {
                    $message->to($order->email)
                        ->subject("🔔 Sesi Baru Dimulai: {$schedule->title}");
                }
            );
        }
    }

    // =========================
    // RULE CHECK (HYBRID - SESI 1 FRIENDLY)
    // =========================
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

    private function canJoinSchedule(Schedule $schedule, int $userId): bool
    {
        $currentSession = $this->getCurrentSession($schedule);
        if (!$currentSession) return true;

        // =========================
        // 🔥 CEK SESSION 1 SUDAH HADIR
        // =========================
        $session1 = $schedule->sessions()
            ->orderBy('session_order', 'asc')
            ->first();

        if ($session1) {
            $alreadyPresentSession1 = DB::table('attendances')
                ->where('class_session_id', $session1->id)
                ->where('user_id', $userId)
                ->where('is_present', 1)
                ->exists();

            // kalau sudah hadir session 1 → bebas lanjut semua session
            if ($alreadyPresentSession1) {
                return true;
            }
        }

        // =========================
        // RULE LAMA (CHAIN VALIDATION)
        // =========================
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
}
