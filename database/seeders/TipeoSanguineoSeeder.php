<?php

namespace Database\Seeders;

use App\Models\TipeoSanguineo;
use Illuminate\Database\Seeder;

class TipeoSanguineoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipeoSanguineo::create([
            'nombre' => 'A +'
        ]);

        TipeoSanguineo::create([
            'nombre' => 'A -'
        ]);

        TipeoSanguineo::create([
            'nombre' => 'B +'
        ]);

        TipeoSanguineo::create([
            'nombre' => 'B -'
        ]);

        TipeoSanguineo::create([
            'nombre' => 'O +'
        ]);

        TipeoSanguineo::create([
            'nombre' => 'AB +'
        ]);

        TipeoSanguineo::create([
            'nombre' => 'AB -'
        ]);
    }
}
