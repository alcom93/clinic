<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Patient;
use App\Models\Admission;

class FullSeeder extends Seeder
{
    public function run()
    {
        // --- Créer 17 chambres ---
        for ($i = 1; $i <= 17; $i++) {
            Room::create([
                'number' => $i,
                'status' => 'libre',
            ]);
        }

        // --- Créer des patients ---
        $patients = [
            ['firstname'=>'Ali', 'lastname'=>'Diop', 'doctor'=>'Dr. Fall'],
            ['firstname'=>'Mamadou', 'lastname'=>'Fall', 'doctor'=>'Dr. Ndiaye'],
            ['firstname'=>'Fatou', 'lastname'=>'Sarr', 'doctor'=>'Dr. Diallo'],
            ['firstname'=>'Awa', 'lastname'=>'Diallo', 'doctor'=>'Dr. Cissé'],
            ['firstname'=>'Ousmane', 'lastname'=>'Ndiaye', 'doctor'=>'Dr. Ba'],
        ];

        foreach ($patients as $p) {
            Patient::create($p);
        }

        // --- Créer des admissions actives ---
        $admissions = [
            ['room_id'=>1, 'patient_id'=>1, 'motif'=>'Césarienne', 'payment_mode'=>'AXA'],
            ['room_id'=>3, 'patient_id'=>2, 'motif'=>'Consultation', 'payment_mode'=>'IPM'],
            ['room_id'=>5, 'patient_id'=>3, 'motif'=>'Accouchement', 'payment_mode'=>'Cash'],
        ];

        foreach ($admissions as $a) {
            Admission::create([
                'room_id'      => $a['room_id'],
                'patient_id'   => $a['patient_id'],
                'admitted_at'  => now(),
                'motif'        => $a['motif'],
                'payment_mode' => $a['payment_mode'],
            ]);

            // Mettre la chambre comme occupée
            $room = Room::find($a['room_id']);
            $room->update(['status' => 'occupee']);
        }
    }
}
