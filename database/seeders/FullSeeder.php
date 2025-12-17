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
        /**
         * ==============================
         * 🏨 CHAMBRES (NUMÉROS MANUELS)
         * ==============================
         */
        $rooms = [
            // 1 lit
            ['number' => '1',  'beds' => 1],
            ['number' => '2',  'beds' => 1],
            ['number' => '3',  'beds' => 1],
            ['number' => '4',  'beds' => 1],
            ['number' => '11', 'beds' => 1],
            ['number' => '12', 'beds' => 1],
            ['number' => '13', 'beds' => 1],
            ['number' => '14', 'beds' => 1],
            ['number' => '15', 'beds' => 1],
            ['number' => '16', 'beds' => 1],
            ['number' => '17', 'beds' => 1],
            ['number' => '21', 'beds' => 1],
            ['number' => '22', 'beds' => 1],
            ['number' => '23', 'beds' => 1],
            ['number' => '24', 'beds' => 1],

            // 2 lits
            ['number' => '20', 'beds' => 2],
            ['number' => '25', 'beds' => 2],

            // Suites
            ['number' => 'ST1', 'beds' => 1],
            ['number' => 'ST2', 'beds' => 1],
            ['number' => 'ST3', 'beds' => 1],
            ['number' => 'ST4', 'beds' => 1],

            // Réanimation
            ['number' => 'REA1', 'beds' => 1],
            ['number' => 'REA2', 'beds' => 1],
        ];

        foreach ($rooms as $room) {
            Room::create([
                'number' => $room['number'],
                'beds'   => $room['beds'],
                'status' => 'libre',
            ]);
        }

        /**
         * ==============================
         * 👤 PATIENTS
         * ==============================
         */
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

        /**
         * ==============================
         * 📝 ADMISSIONS
         * ==============================
         */
        $admissions = [
            ['room_number'=>'1',  'patient_id'=>1, 'motif'=>'Césarienne',  'payment_mode'=>'AXA'],
            ['room_number'=>'3',  'patient_id'=>2, 'motif'=>'Consultation','payment_mode'=>'IPM'],
            ['room_number'=>'20', 'patient_id'=>3, 'motif'=>'Observation', 'payment_mode'=>'Cash'],
        ];

        foreach ($admissions as $a) {
            $room = Room::where('number', $a['room_number'])->first();

            Admission::create([
                'room_id'      => $room->id,
                'patient_id'   => $a['patient_id'],
                'admitted_at'  => now(),
                'motif'        => $a['motif'],
                'payment_mode' => $a['payment_mode'],
            ]);

            // chambre occupée si lits pleins (logique simple pour le seed)
            $room->update(['status' => 'occupee']);
        }
    }
}
