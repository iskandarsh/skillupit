<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MentorController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return Mentor::with('kelas')->latest()->get();
        }

        $kelas = Kelas::select('id', 'nama_kelas')->get();

        return view('mentor', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:mentors,email',
            'job_title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kelas_ids' => 'nullable|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        // 🔥 VALIDASI KELAS SUDAH ADA MENTOR
        $this->validateKelasMentor($request->kelas_ids);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('mentor', 'public');
        }

        $mentor = Mentor::create([
            'name' => $request->name,
            'email' => $request->email,
            'job_title' => $request->job_title,
            'bio' => $request->bio,
            'photo' => $photoPath,
        ]);

        $mentor->kelas()->sync($request->kelas_ids ?? []);

        return response()->json([
            'status' => true,
            'message' => 'Mentor berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $mentor = Mentor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:mentors,email,' . $id,
            'job_title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kelas_ids' => 'nullable|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        // 🔥 VALIDASI (exclude mentor ini sendiri)
        $this->validateKelasMentor($request->kelas_ids, $id);

        $photoPath = $mentor->photo;

        if ($request->hasFile('photo')) {
            if ($mentor->photo && Storage::disk('public')->exists($mentor->photo)) {
                Storage::disk('public')->delete($mentor->photo);
            }

            $photoPath = $request->file('photo')->store('mentor', 'public');
        }

        $mentor->update([
            'name' => $request->name,
            'email' => $request->email,
            'job_title' => $request->job_title,
            'bio' => $request->bio,
            'photo' => $photoPath,
        ]);

        $mentor->kelas()->sync($request->kelas_ids ?? []);

        return response()->json([
            'status' => true,
            'message' => 'Mentor berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $mentor = Mentor::findOrFail($id);

        if ($mentor->photo && Storage::disk('public')->exists($mentor->photo)) {
            Storage::disk('public')->delete($mentor->photo);
        }

        $mentor->kelas()->detach();
        $mentor->delete();

        return response()->json([
            'status' => true,
            'message' => 'Mentor berhasil dihapus'
        ]);
    }

    private function validateKelasMentor($kelasIds, $mentorId = null)
    {
        if (!$kelasIds) return;

        foreach ($kelasIds as $kelasId) {
            $exists = \DB::table('kelas_mentor')
                ->where('kelas_id', $kelasId)
                ->when($mentorId, function ($q) use ($mentorId) {
                    $q->where('mentor_id', '!=', $mentorId); // exclude diri sendiri saat update
                })
                ->exists();

            if ($exists) {
                abort(422, "Kelas ID {$kelasId} sudah memiliki mentor lain.");
            }
        }
    }
}
