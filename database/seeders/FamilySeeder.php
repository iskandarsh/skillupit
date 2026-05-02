<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FamilyMember;
use App\Models\Marriage;

class FamilySeeder extends Seeder
{
    public function run()
    {
        // =========================
        // GENERASI 1 (KAKEK NENEK)
        // =========================
        $kakek = FamilyMember::create([
            'name' => 'Ahmad',
            'gender' => 'male',
            'role' => 'Kakek'
        ]);

        $nenek = FamilyMember::create([
            'name' => 'Fatimah',
            'gender' => 'female',
            'role' => 'Nenek'
        ]);

        Marriage::create([
            'husband_id' => $kakek->id,
            'wife_id' => $nenek->id,
            'married_at' => '1970-01-01'
        ]);

        // =========================
        // GENERASI 2 (ANAK)
        // =========================
        $anak1 = FamilyMember::create([
            'name' => 'Budi',
            'gender' => 'male',
            'parent_id' => $kakek->id
        ]);

        $anak2 = FamilyMember::create([
            'name' => 'Siti',
            'gender' => 'female',
            'parent_id' => $kakek->id
        ]);

        // =========================
        // PASANGAN ANAK 1 (CERAI)
        // =========================
        $istri1 = FamilyMember::create([
            'name' => 'Ani',
            'gender' => 'female'
        ]);

        $marriage1 = Marriage::create([
            'husband_id' => $anak1->id,
            'wife_id' => $istri1->id,
            'married_at' => '2000-01-01',
            'divorced_at' => '2010-01-01' // 🔥 CERAI
        ]);

        // =========================
        // NIKAH LAGI
        // =========================
        $istri2 = FamilyMember::create([
            'name' => 'Rina',
            'gender' => 'female'
        ]);

        Marriage::create([
            'husband_id' => $anak1->id,
            'wife_id' => $istri2->id,
            'married_at' => '2012-01-01'
        ]);

        // =========================
        // ANAK DARI ISTRI 1
        // =========================
        $cucu1 = FamilyMember::create([
            'name' => 'Doni',
            'gender' => 'male',
            'parent_id' => $anak1->id
        ]);

        // =========================
        // ANAK DARI ISTRI 2
        // =========================
        $cucu2 = FamilyMember::create([
            'name' => 'Lina',
            'gender' => 'female',
            'parent_id' => $anak1->id
        ]);

        // =========================
        // ANAK 2 (NORMAL)
        // =========================
        $suamiAnak2 = FamilyMember::create([
            'name' => 'Rudi',
            'gender' => 'male'
        ]);

        Marriage::create([
            'husband_id' => $suamiAnak2->id,
            'wife_id' => $anak2->id,
            'married_at' => '2005-01-01'
        ]);

        // =========================
        // CUCU DARI ANAK 2
        // =========================
        FamilyMember::create([
            'name' => 'Putri',
            'gender' => 'female',
            'parent_id' => $anak2->id
        ]);

        // =========================
        // TAMBAH STATUS MENINGGAL
        // =========================
        $kakek->update([
            'is_dead' => true,
            'death_date' => '2020-01-01'
        ]);
    }
}
