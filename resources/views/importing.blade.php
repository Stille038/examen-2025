@extends('layouts.app')

@section('title', 'test1')

@section('content')
<div class="bg-primary rounded container mt-10 pb-10 p-5 mb-40 text-base/8">
    <div>
        <h1 class="text-2xl font-bold ">
            Gegevens Importeren
        </h1>
        <p class="border-b border-primary pb-5 ">
            Upload spreadsheet bestanden met aanwezigheidsgegevens
        </p>
    </div>
    <div class="border-2 mt-10 border-dashed border-primary rounded-lg p-8 text-center container mx-auto bg-white">
        <div class="text-4xl mb-4">📁</div>
        <h2 class="font-semibold text-gray-700">Sleep bestanden hierheen of klik om te selecteren</h2>
        <p class="text-sm text-gray-500 mt-2">Ondersteunde formaten: .xlsx, .xls</p>

        <form action="{{ route('excel.upload') }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <input id="file-upload" type="file" name="bestand" accept=".xlsx,.ods" class="hidden">
            <button type="submit" class="px-4 py-2 bg-secondary-light hover:bg-blue-600 text-white text-sm rounded inline-flex items-center">
                📁 Bestanden uploaden
            </button>

            @if ($errors->has('bestand'))
                <div class="text-red-600 mt-2">
                    ❌ {{ $errors->first('bestand') }}
                </div>
            @endif
        </form>

        @if (session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-800 rounded border border-green-300 inline-block">
                ✅ {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="mt-5">
        <h2 class="text-lg font-semibold mb-4">Import Geschiedenis (Laatste 10)</h2>

        <div class="bg-white shadow rounded-lg ">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-primary border-b text-xs font-semibold uppercase">
                    <tr>
                        <th class="px-6 py-3">Bestand</th>
                        <th class="px-6 py-3">Datum/Tijd</th>
                        <th class="px-6 py-3">Records</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)    
                        <tr class="border-b hover:bg-hover">
                            <td class="px-6 py-4">
                                <div class="font-medium text-blue-600">{{ $log->filename }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($log->created_at)->isoFormat('dddd D MMMM YYYY') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $log->created_at->format('d-m-Y') }}<br>
                                <span class="text-xs">{{ $log->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $log->imported_rows ?? 0 }} records
                            </td>
                            <td class="px-6 py-4
                                @if($log->status === 'success') text-green-600
                                @elseif($log->status === 'warning') text-yellow-600
                                @else text-red-600 @endif">
                                @if($log->status === 'success')
                                    ✔️ Succesvol
                                @elseif($log->status === 'warning')
                                    ⚠️ Met waarschuwingen
                                @else
                                    ❌ Fout
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Geen importgeschiedenis gevonden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-blue-300 mt-5 text-blue-500 p-5 rounded text-base/8">
            <h3>
                💡 Import tips:
            </h3>
            <li>Eerste rij moet kolomname bevatten</li>
            <li>Meerdere bestanden voor dezelfde week? Laatste bestand is leidend</li>
            <li>Nieuwe student worden automatisch ingezet</li>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file-upload');
    const form = document.querySelector('form');

    form.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            fileInput.click();
        }
    });

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length) {
            form.submit();
        }
    });
});
</script>
@endsection
