<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\ClassSession;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {

            return Assignment::with([
                'session.schedule.kelas',
                'submissions'
            ])
                ->latest()
                ->get()
                ->map(function ($item) {

                    $item->submitted = $item->submissions->count();

                    return $item;
                });
        }

        $sessions = ClassSession::with('schedule.kelas')
            ->orderBy('session_order')
            ->get();

        return view('assignment', compact('sessions'));
    }
    public function updateSubmission(Request $request, $id)
    {
        $submission = AssignmentSubmission::findOrFail($id);

        $submission->update([
            'score'    => $request->score,
            'feedback' => $request->feedback,
            'status'   => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil disimpan'
        ]);
    }
    public function submissions($id)
    {
        $assignment = Assignment::with([
            'submissions.user'
        ])->findOrFail($id);

        return response()->json(
            $assignment->submissions
        );
    }


    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'required|file|max:10240',
        ]);

        $file = $request->file('file');

        $path = $file->store('assignment-submissions', 'public');

        // dd($assignment->session->schedule->kelas_id);
        AssignmentSubmission::create([
            'kelas_id'      => $assignment->session->schedule->kelas_id,
            'assignment_id' => $assignment->id,
            'user_id'       => auth()->id(),
            'title'         => $request->title,
            'description'   => $request->description,
            'file'          => $path,
            'status'        => 'submitted',
            'submitted_at'  => now(),
        ]);

        return back()->with('success', 'Assignment berhasil dikumpulkan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_session_id' => 'required|exists:class_sessions,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'deadline'         => 'nullable|date',
        ]);

        Assignment::create([
            'class_session_id' => $request->class_session_id,
            'title'            => $request->title,
            'description'      => $request->description,
            'deadline'         => $request->deadline,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Assignment berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $request->validate([
            'class_session_id' => 'required|exists:class_sessions,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'deadline'         => 'nullable|date',
        ]);

        $assignment->update([
            'class_session_id' => $request->class_session_id,
            'title'            => $request->title,
            'description'      => $request->description,
            'deadline'         => $request->deadline,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Assignment berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);

        $assignment->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Assignment berhasil dihapus'
        ]);
    }
}
