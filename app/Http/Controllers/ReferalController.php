<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferalController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return Referral::with(['user', 'kelas'])->latest()->get();
        }

        $users = User::all();
        $kelas = Kelas::all();

        return view('referral', compact('users', 'kelas'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'kode' => 'required|unique:referrals,kode',
            'user_id' => 'nullable|exists:users,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'disc' => 'nullable|integer|min:0|max:100'
        ]);

        Referral::create([
            'kode' => $request->kode,
            'user_id' => $request->user_id ?: null,
            'kelas_id' => $request->kelas_id ?: null,
            'disc' => $request->disc ?: null,
        ]);

        return response()->json(['message' => 'Referal berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:referrals,kode,' . $id,
            'user_id' => 'nullable|exists:users,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'disc' => 'nullable|integer|min:0|max:100'
        ]);

        $ref = Referral::findOrFail($id);

        $ref->update([
            'kode' => $request->kode,
            'user_id' => $request->user_id ?: null,
            'kelas_id' => $request->kelas_id ?: null,
            'disc' => $request->disc ?: null,
        ]);

        return response()->json(['message' => 'Referal berhasil diupdate']);
    }

    public function destroy($id)
    {
        Referral::findOrFail($id)->delete();

        return response()->json(['message' => 'Referal berhasil dihapus']);
    }

    public function checkReferral(Request $request)
    {
        $ref = Referral::where('kode', $request->kode)->first();

        if (!$ref) {
            return response()->json(['valid' => false]);
        }

        return response()->json([
            'valid' => true,
            'disc' => $ref->disc
        ]);
    }
}
