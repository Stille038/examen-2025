<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AanwezighedenSeeder extends Seeder
{
    public function run(): void
    {
        $groepen = ['ICT-FLEX', 'ICT-REGULIER', 'ICT-FASTTRACK'];

        // Pak alle rijen zonder groep
        $aanwezigheden = DB::table('aanwezigheden')
            ->whereNull('groep')
            ->get();

        foreach ($aanwezigheden as $aanwezigheid) {
            DB::table('aanwezigheden')
                ->where('id', $aanwezigheid->id)
                ->update([
                    'groep' => $groepen[array_rand($groepen)],
                ]);
        }
    }
}
