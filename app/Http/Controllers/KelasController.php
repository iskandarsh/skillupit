<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use Illuminate\Support\Facades\File;

class KelasController extends Controller
{
    public function index()
    {
        return view('kelas');
    }

    public function data()
    {
        return response()->json(Kelas::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'  => 'required|string',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric',
            'harga_coret' => 'nullable|numeric',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'   => 'nullable',
            'periode'     => 'nullable|date',
            'kategori'    => 'nullable|in:IT,Academic',
        ]);

        $isActive = 0;
        if ($request->has('is_active')) {
            $val = $request->input('is_active');
            if ($val === true || $val === 1 || $val === "1" || $val === "true") {
                $isActive = 1;
            }
        }

        $periode = null;
        if ($request->periode) {
            $periode = date('Y-m-d', strtotime($request->periode));
        }

        $data = [
            'nama_kelas'  => $request->nama_kelas,
            'deskripsi'   => $request->deskripsi,
            'harga'       => $request->harga,
            'harga_coret'  => $request->harga_coret,
            'is_active'   => $isActive,
            'periode'     => $periode,
            'kategori'    => $request->kategori,
        ];

        if ($request->hasFile('thumbnail')) {
            $path = public_path('uploads/kelas');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);

            $data['thumbnail'] = 'uploads/kelas/' . $filename;
        }

        $kelas = Kelas::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan',
            'data' => $kelas
        ]);
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas'  => 'required|string',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric',
            'harga_coret' => 'nullable|numeric',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'   => 'nullable',
            'periode'     => 'nullable|date',
            'kategori'    => 'nullable|in:IT,Academic',
        ]);

        $inputs = $request->all();

        if ($request->has('is_active')) {
            $inputs['is_active'] = ($request->is_active === 'true' || $request->is_active == 1) ? 1 : 0;
        }

        if ($request->has('periode') && $request->periode) {
            $inputs['periode'] = date('Y-m-d', strtotime($request->periode));
        }

        if ($request->hasFile('thumbnail')) {
            if ($kelas->thumbnail && File::exists(public_path($kelas->thumbnail))) {
                File::delete(public_path($kelas->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/kelas'), $filename);
            $inputs['thumbnail'] = 'uploads/kelas/' . $filename;
        }

        $kelas->update($inputs);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $kelas
        ]);
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->thumbnail && File::exists(public_path($kelas->thumbnail))) {
            File::delete(public_path($kelas->thumbnail));
        }

        $kelas->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
