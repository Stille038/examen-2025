<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aanwezigheid;

class AanwezighedenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['studentnummer' => 'st1121615364', 'aanwezigheid' => 900, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st1463641365', 'aanwezigheid' => 720, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st2473322711', 'aanwezigheid' => 891, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st2615331524', 'aanwezigheid' => 1,   'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st3255555757', 'aanwezigheid' => 123, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st3725372731', 'aanwezigheid' => 479, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st4413532123', 'aanwezigheid' => 545, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st4564711124', 'aanwezigheid' => 688, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st4635631175', 'aanwezigheid' => 930, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
            ['studentnummer' => 'st5172541747', 'aanwezigheid' => 500, 'rooster' => 900, 'week' => 49, 'jaar' => 2024],
        ];

        foreach ($data as $record) {
            Aanwezigheid::create($record); // hier boven is dummy content dat foreach loopt het doorheen en zet dat in tabel in db 
        }
    }
}
