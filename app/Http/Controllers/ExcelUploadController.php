<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aanwezigheid;
use App\Models\ImportLog;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Ods;
use Illuminate\Support\Facades\Log;

class ExcelUploadController extends Controller
{
    public function showImportForm()
    {
        $logs = ImportLog::latest()->take(10)->get(); // ✅ Get latest 10 logs
        return view('importing', compact('logs'));     // ✅ Pass to Blade
    }

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

        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Create a new import log
        $importLog = ImportLog::create([
            'filename' => $filename,
            'status' => 'processing',
        ]);

        try {
            $reader = match (strtolower($extension)) {
                'xlsx' => new Xlsx(),
                'xls' => new Xls(),
                'ods' => new Ods(),
                default => throw new \Exception('❌ Ongeldig bestandsformaat.')
            };

            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $skipped = [];
            $importedCount = 0;

            foreach (array_slice($rows, 1) as $i => $row) {
                try {
                    if (!empty($row[0]) && isset($row[4])) {
                        $exists = Aanwezigheid::where('studentnummer', $row[0])
                            ->where('week', $row[3])
                            ->where('jaar', $row[4])
                            ->exists();

                        if ($exists) {
                            Log::info("⏭️ Row $i already exists: " . $row[0]);
                            continue;
                        }

                        Aanwezigheid::create([
                            'studentnummer'    => $row[0],
                            'aanwezigheid'     => $row[1],
                            'rooster'          => $row[2],
                            'week'             => $row[3],
                            'jaar'             => $row[4],
                            'import_filename'  => $filename,
                            'import_log_id'    => $importLog->id,
                        ]);

                        $importedCount++;
                        Log::info("✅ Row $i inserted.", $row);
                    } else {
                        $skipped[] = $row;
                        Log::warning("⚠️ Row $i skipped (incomplete):", $row);
                    }
                } catch (\Throwable $e) {
                    $skipped[] = $row;
                    Log::error("🔥 Error in row $i: " . $e->getMessage());
                }
            }

            $importLog->status = 'success';
            $importLog->imported_rows = $importedCount;
            $importLog->error_rows = json_encode($skipped);
            $importLog->save();

            return back()->with('success', '✅ Excel succesvol geïmporteerd!');
        } catch (\Throwable $e) {
            $importLog->status = 'failed';
            $importLog->error_message = $e->getMessage();
            $importLog->save();

            Log::error("❌ Import failed: " . $e->getMessage());
            return back()->withErrors(['bestand' => '❌ Importeren mislukt.']);
        }
    }
}
