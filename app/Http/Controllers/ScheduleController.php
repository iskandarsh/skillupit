<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Kelas;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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

    public function uploadRecord(Request $request, $id)
    {
        $request->validate([
            'record_video' => 'required|mimes:mp4,mov,avi,mkv,webm|max:512000',
        ]);

        $schedule = Schedule::findOrFail($id);

        // folder
        $path = public_path('uploads/record-video');

        // create folder if not exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // upload file
        if ($request->hasFile('record_video')) {

            $file = $request->file('record_video');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move($path, $filename);

            // delete old file
            if ($schedule->record_video && File::exists(public_path($schedule->record_video))) {
                File::delete(public_path($schedule->record_video));
            }

            $schedule->record_video = 'uploads/record-video/' . $filename;
        }

        $schedule->save();

        return response()->json([
            'success' => true,
            'message' => 'Video record berhasil diupload'
        ]);
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
            ->where('status_kelas', 0)
            ->get();

        $users = User::whereIn('email', $participants->pluck('email'))
            ->get()
            ->keyBy('email');

        $presentUsers = DB::table('attendances')
            ->where('class_session_id', $session->id)
            ->where('is_present', 1)
            ->pluck('user_id')
            ->toArray();


        $dtStart = date('Ymd\THis', strtotime($schedule->start_time));
        $dtEnd = date('Ymd\THis', strtotime($schedule->end_time));
        $dtStamp = date('Ymd\THis');
        $uid = uniqid();

        $icsContent = "BEGIN:VCALENDAR\n" .
            "VERSION:2.0\n" .
            "PROID:-//SkillUpIT//Calendar//ID\n" .
            "BEGIN:VEVENT\n" .
            "DTSTAMP:{$dtStamp}Z\n" .
            "DTSTART:{$dtStart}Z\n" .
            "DTEND:{$dtEnd}Z\n" .
            "UID:{$uid}\n" .
            "SUMMARY:{$schedule->title}\n" .
            "LOCATION:{$schedule->link}\n" .
            "END:VEVENT\n" .
            "END:VCALENDAR";


        foreach ($participants as $order) {
            $user = $users->get($order->email);
            if (!$user) continue;

            // sudah hadir di session yang sama / session_order yang sama
            if (
                in_array($user->id, $presentUsers) ||
                $this->hasPresentAttendanceForSessionOrder($schedule, $user->id, $session->session_order)
            ) {
                continue;
            }

            if (!$this->canJoinSchedule($schedule, $user->id)) {
                continue;
            }

            // 1. Buat Konten File ICS (Google Calendar)


            $emailBody = "Halo {$order->nama},\n\n" .
                "Sesi baru telah dimulai 🎓\n\n" .
                "📚 Kelas: {$schedule->title}\n" .
                "⏰ Waktu: {$schedule->start_time} - {$schedule->end_time}\n\n" .
                "🔗 Link : {$schedule->link}\n\n" .
                "Tetap konsisten ya, biar tidak tertinggal materi 🚀";

            // Gunakan struktur ini untuk menghindari TypeError
            Mail::send([], [], function ($message) use ($order, $schedule, $icsContent, $emailBody) {
                $message->to($order->email)
                    ->subject("🔔 Sesi Baru Dimulai: {$schedule->title}")
                    // Gunakan html() atau plain() untuk mengisi body
                    ->html(nl2br($emailBody))
                    ->text($emailBody)
                    ->attachData($icsContent, "invite.ics", [
                        'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
                    ]);
            });
        }
    }

    private function hasPresentAttendanceForSessionOrder(Schedule $schedule, int $userId, int $sessionOrder): bool
    {
        return DB::table('attendances as a')
            ->join('class_sessions as s', 'a.class_session_id', '=', 's.id')
            ->join('schedules as sc', 's.schedule_id', '=', 'sc.id')
            ->where('sc.kelas_id', $schedule->kelas_id)
            ->where('s.session_order', $sessionOrder)
            ->where('a.user_id', $userId)
            ->where('a.is_present', 1)
            ->exists();
    }

    // private function canJoinSchedule(Schedule $schedule, int $userId): bool
    // {
    //     $currentSession = $this->getCurrentSession($schedule);
    //     if (!$currentSession) return true;

    //     // Kalau user sudah pernah hadir di session_order yang sama, langsung boleh lanjut
    //     if ($this->hasPresentAttendanceForSessionOrder($schedule, $userId, $currentSession->session_order)) {
    //         return true;
    //     }

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

    private function hasAnyAttendanceInClass(Schedule $schedule, int $userId): bool
    {
        return DB::table('attendances as a')
            ->join('class_sessions as s', 'a.class_session_id', '=', 's.id')
            ->join('schedules as sc', 's.schedule_id', '=', 'sc.id')
            ->where('sc.kelas_id', $schedule->kelas_id)
            ->where('a.user_id', $userId)
            ->exists();
    }

    private function hasPresentAttendanceBeforeSessionOrder(Schedule $schedule, int $userId, int $sessionOrder): bool
    {
        return DB::table('attendances as a')
            ->join('class_sessions as s', 'a.class_session_id', '=', 's.id')
            ->join('schedules as sc', 's.schedule_id', '=', 'sc.id')
            ->where('sc.kelas_id', $schedule->kelas_id)
            ->where('a.user_id', $userId)
            ->where('a.is_present', 1)
            ->where('s.session_order', '<', $sessionOrder)
            ->exists();
    }


    // private function canJoinSchedule(Schedule $schedule, int $userId): bool
    // {
    //     $currentSession = $this->getCurrentSession($schedule);
    //     if (!$currentSession) return true;

    //     // Kalau user belum punya attendance sama sekali di kelas ini,
    //     // langsung boleh join
    //     if (!$this->hasAnyAttendanceInClass($schedule, $userId)) {
    //         return true;
    //     }

    //     // Kalau user sudah pernah hadir di session_order yang sama, langsung boleh lanjut
    //     if ($this->hasPresentAttendanceForSessionOrder($schedule, $userId, $currentSession->session_order)) {
    //         return true;
    //     }

    //     // CEK SESSION 1 SUDAH HADIR
    //     $session1 = $schedule->sessions()
    //         ->orderBy('session_order', 'asc')
    //         ->first();

    //     if ($session1) {
    //         $alreadyPresentSession1 = DB::table('attendances')
    //             ->where('class_session_id', $session1->id)
    //             ->where('user_id', $userId)
    //             ->where('is_present', 1)
    //             ->exists();

    //         if ($alreadyPresentSession1) {
    //             return true;
    //         }
    //     }

    //     // RULE LAMA (CHAIN VALIDATION)
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
        $currentSession = $this->getCurrentSession($schedule);
        if (!$currentSession) return true;

        // Kalau user belum punya attendance sama sekali di kelas ini, langsung boleh join
        if (!$this->hasAnyAttendanceInClass($schedule, $userId)) {
            return true;
        }

        // Kalau user sudah pernah hadir di session_order yang sama, langsung boleh lanjut
        if ($this->hasPresentAttendanceForSessionOrder($schedule, $userId, $currentSession->session_order)) {
            return true;
        }

        // INI TAMBAHAN:
        // Kalau user sudah hadir di session_order yang lebih kecil, dia boleh join session berikutnya
        if ($this->hasPresentAttendanceBeforeSessionOrder($schedule, $userId, $currentSession->session_order)) {
            return true;
        }

        // CEK SESSION 1 SUDAH HADIR
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

        // RULE LAMA (CHAIN VALIDATION)
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
