<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modul;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModulController extends Controller
{
    public function data($kelas_id)
    {
        $data = Modul::where('kelas_id', $kelas_id)
            ->latest()
            ->get();

        return response()->json($data);
    }

    public function store(Request $request, $kelas_id)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:10240' // max 10MB
        ]);

        DB::beginTransaction();

        try {
            $files = $request->file('files');

            foreach ($files as $file) {

                $originalName = $file->getClientOriginalName();
                $size = $file->getSize();

                $folder = public_path('uploads/modul/' . $kelas_id);

                if (!File::exists($folder)) {
                    File::makeDirectory($folder, 0755, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($folder, $filename);

                Modul::create([
                    'kelas_id' => $kelas_id,
                    'original_name' => $originalName,
                    'file_path' => 'uploads/modul/' . $kelas_id . '/' . $filename,
                    'file_size' => $size,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Semua modul berhasil diupload'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal upload modul',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $modul = Modul::findOrFail($id);

        if ($modul->file_path && file_exists(public_path($modul->file_path))) {
            unlink(public_path($modul->file_path));
        }

        $modul->delete();

        return response()->json([
            'message' => 'Modul berhasil dihapus'
        ]);
    }
}
