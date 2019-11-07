<?php

use Illuminate\Database\Seeder;

class EquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Equipment::create([
            'name' => 'HEAD OFFICE',
            'children' => [
                [
                    'name' => 'Chiller Equipment',
                    'children' => [
                        [
                            'name' => 'H.O Chiller 1',
                            //'children' => [
                            //    '1 Circuit',
                            //    '2 Circuit',
                            //    '3 Circuit',
                            //    '4 Circuit',
                            //    '5 Circuit',
                            //],
                        ]
                    ],
                ]
            ]
        ]);

        $hoChiller = \App\Models\Equipment::where('name', 'H.O Chiller 1')->first()->children()->create(['name' => '1 Circuit']);
        $hoChiller = \App\Models\Equipment::where('name', 'H.O Chiller 1')->first()->children()->create(['name' => '2 Circuit']);
        $hoChiller = \App\Models\Equipment::where('name', 'H.O Chiller 1')->first()->children()->create(['name' => '3 Circuit']);
        $hoChiller = \App\Models\Equipment::where('name', 'H.O Chiller 1')->first()->children()->create(['name' => '4 Circuit']);
        $hoChiller = \App\Models\Equipment::where('name', 'H.O Chiller 1')->first()->children()->create(['name' => '5 Circuit']);
    }
}
