<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Ods;

class ExcelUploadController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('🟢 ExcelUploadController@store reached');

        $request->validate([
            'bestand' => 'required|file|mimes:xlsx,xls,ods|max:2048',
        ]);

        $file = $request->file('bestand');

        if (!$file->isValid()) {
            return back()->withErrors(['bestand' => '❌ Ongeldig bestand.']);
        }

        $extension = $file->getClientOriginalExtension();

        switch (strtolower($extension)) {
            case 'xlsx':
                $reader = new Xlsx();
                break;
            case 'xls':
                $reader = new Xls();
                break;
            case 'ods':
                $reader = new Ods();
                break;
            default:
                return back()->withErrors(['bestand' => '❌ Ongeldig bestandsformaat.']);
        }

        $spreadsheet = $reader->load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Skip the header row
        foreach (array_slice($rows, 1) as $i => $row) {
            // Basic validation per row
            if (!empty($row[0]) && isset($row[4])) {
                Aanwezigheid::create([
                    'studentnummer' => $row[0],
                    'aanwezigheid'  => $row[1],
                    'rooster'       => $row[2],
                    'week'          => $row[3],
                    'jaar'          => $row[4],
                ]);
                \Log::info("✅ Row {$i} inserted.", $row);
            } else {
                \Log::warning("⚠️ Row {$i} skipped (incomplete):", $row);
            }
        }

        return back()->with('success', '✅ Excel succesvol geïmporteerd!');
    }
}
