<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class FullSeeder extends Seeder
{
    public function run()
    {
        /**
         * ==============================
         * 🏨 CHAMBRES
         * ==============================
         */
        $rooms = [
            ['number'=>'1','beds'=>1], ['number'=>'2','beds'=>1],
            ['number'=>'3','beds'=>1], ['number'=>'4','beds'=>1],
            ['number'=>'11','beds'=>1], ['number'=>'12','beds'=>1],
            ['number'=>'13','beds'=>1], ['number'=>'14','beds'=>1],
            ['number'=>'15','beds'=>1], ['number'=>'16','beds'=>1],
            ['number'=>'17','beds'=>1],
            ['number'=>'20(1)','beds'=>1], ['number'=>'20(2)','beds'=>1],
            ['number'=>'21','beds'=>1], ['number'=>'22','beds'=>1],
            ['number'=>'23','beds'=>1], ['number'=>'24','beds'=>1],
            ['number'=>'25(1)','beds'=>1], ['number'=>'25(2)','beds'=>1],
            ['number'=>'ST1','beds'=>1], ['number'=>'ST2','beds'=>1],
            ['number'=>'ST3','beds'=>1], ['number'=>'ST4','beds'=>1],
            ['number'=>'REA1','beds'=>1], ['number'=>'REA2','beds'=>1],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(
                ['number' => $room['number']],
                [
                    'beds'   => $room['beds'],
                    'status' => 'libre'
                ]
            );
        }
    }
}
