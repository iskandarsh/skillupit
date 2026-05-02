<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use App\Models\Marriage;
use Illuminate\Http\Request;

class FamilyTreeController extends Controller
{
    private $processed = [];

    public function index()
    {

        $root = FamilyMember::with([
            'children',
            'marriagesAsHusband.wife',
            'marriagesAsWife.husband'
        ])->whereNull('parent_id')->first();

        $this->processed = [];

        $familyData = $this->formatTree($root);
        // dd($familyData);
        return view('family-tree', compact('familyData'));
    }

    // =========================
    // TREE BUILDER
    // =========================
    private function formatTree($member, $level = 0)
    {
        if (!$member || in_array($member->id, $this->processed)) return null;

        $this->processed[] = $member->id;

        // Logic 2 Level: Jika level >= 1, maka node ini akan menutup anak-anaknya (cucu dst)
        $isCollapsed = ($level >= 1);

        $marriages = $member->marriagesAsHusband->merge($member->marriagesAsWife);
        $nodes = [];

        if ($marriages->count() > 0) {
            foreach ($marriages as $marriage) {
                $partner = $marriage->husband_id == $member->id ? $marriage->wife : $marriage->husband;
                if (!$partner || in_array($partner->id, $this->processed)) continue;

                $this->processed[] = $partner->id;

                $node = [
                    'type' => 'couple',
                    'name' => $member->name ?? '–',
                    'partnerName' => $partner->name ?? '–',
                    'role' => $member->role ?? 'Keluarga',
                    'partnerRole' => $partner->role ?? 'Keluarga',
                    'img1' => $member->photo ? asset($member->photo) : 'https://i.pravatar.cc/150?img=1',
                    'img2' => $partner->photo ? asset($partner->photo) : 'https://i.pravatar.cc/150?img=2',
                    'status' => $marriage->divorced_at ? 'divorced' : 'married',
                    'death_date1' => $member->is_dead && $member->death_date ? "{$member->name} meninggal – {$member->death_date}" : null,
                    'death_date2' => $partner->is_dead && $partner->death_date ? "{$partner->name} meninggal – {$partner->death_date}" : null,
                    'collapsed' => $isCollapsed, // Tambahkan ini
                    'children' => []
                ];

                $children = FamilyMember::where(function ($q) use ($member, $partner) {
                    $q->where('parent_id', $member->id)->orWhere('parent_id', $partner->id);
                })->get();

                foreach ($children as $child) {
                    // Tambahkan level + 1
                    $childNode = $this->formatTree($child, $level + 1);
                    if ($childNode) $node['children'][] = $childNode;
                }
                $nodes[] = $node;
            }

            if (count($nodes) === 1) return $nodes[0];

            return [
                'type' => 'multiple',
                'name' => "Keluarga " . ($member->name ?? '–'),
                'collapsed' => $isCollapsed, // Tambahkan ini
                'children' => $nodes
            ];
        }

        // SINGLE MEMBER
        $node = [
            'type' => 'single',
            'name' => $member->name,
            'role' => $member->role ?? 'Keluarga',
            'gender' => $member->gender,
            'img' => $member->photo ? asset($member->photo) : 'https://i.pravatar.cc/150',
            'death_date' => $member->is_dead ? $member->death_date : null,
            'collapsed' => $isCollapsed, // Tambahkan ini
            'children' => []
        ];

        foreach ($member->children as $child) {
            // Tambahkan level + 1
            $childNode = $this->formatTree($child, $level + 1);
            if ($childNode) $node['children'][] = $childNode;
        }

        return $node;
    }



    // API Data untuk DataTable
    public function data()
    {
        return FamilyMember::with(['parent', 'marriagesAsHusband.wife', 'marriagesAsWife.husband'])->get();
    }

    // Pencarian untuk Select2
    public function search(Request $request)
    {
        return FamilyMember::where('name', 'like', "%{$request->q}%")
            ->select('id', 'name as text')->limit(10)->get();
    }

    // Simpan Member & Otomatis Buat Pernikahan jika Partner dipilih
    // public function store(Request $request)
    // {
    //     $member = FamilyMember::create($request->only(['name', 'role', 'gender', 'parent_id', 'photo']));

    //     if ($request->partner_id) {
    //         Marriage::create([
    //             'husband_id' => $member->gender == 'male' ? $member->id : $request->partner_id,
    //             'wife_id'    => $member->gender == 'female' ? $member->id : $request->partner_id,
    //             'married_at' => now()
    //         ]);
    //     }
    //     return response()->json($member);
    // }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'role', 'gender', 'parent_id']);

        // Logika Upload Foto
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/family'), $filename);
            $data['photo'] = '/uploads/family/' . $filename; // Simpan path-nya
        } else {
            $data['photo'] = 'https://i.pravatar.cc/150'; // Default jika tidak ada foto
        }

        $member = FamilyMember::create($data);

        if ($request->partner_id) {
            Marriage::create([
                'husband_id' => $member->gender == 'male' ? $member->id : $request->partner_id,
                'wife_id'    => $member->gender == 'female' ? $member->id : $request->partner_id,
                'married_at' => now()
            ]);
        }
        return response()->json($member);
    }

    public function update(Request $request, $id)
    {
        $member = FamilyMember::findOrFail($id);
        $data = $request->only(['name', 'role', 'gender']);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada (opsional)
            if ($member->photo && file_exists(public_path($member->photo))) {
                @unlink(public_path($member->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/family'), $filename);
            $data['photo'] = '/uploads/family/' . $filename;
        }

        $member->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Marriage::where('husband_id', $id)->orWhere('wife_id', $id)->delete();
        FamilyMember::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // =========================
    // CERAI
    // =========================
    public function divorce($id)
    {
        $marriage = \App\Models\Marriage::findOrFail($id);

        if ($marriage->divorced_at) {
            return response()->json(['success' => false, 'message' => 'Status sudah cerai.']);
        }

        // Update tanggal cerai menjadi sekarang
        $marriage->update([
            'divorced_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Perceraian berhasil dicatat.'
        ]);
    }
}
