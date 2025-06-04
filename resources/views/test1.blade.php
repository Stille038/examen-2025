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

        <form action="{{ route('excel.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="file-upload" class="cursor-pointer mt-4 px-4 py-2 bg-secondary-light  hover:bg-blue-600 text-white text-sm rounded inline-block">
                📁 Bestanden selecteren
            </label>
            <input id="file-upload" type="file" name="bestand" accept=".xls,.ods" class="hidden" onchange="this.form.submit()">
        </form>


        @if (session('success'))
        <div class="mt-6 p-3 bg-green-100 text-green-800 rounded border border-green-300 shadow inline-block">
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
                    <tr class="border-b hover:bg-hover">
                        <td class="px-6 py-4">
                            <div class="font-medium text-blue-600">aanwezigheid_week8_2025.xlsx</div>
                            <div class="text-xs text-gray-500">Week 8, 2025</div>
                        </td>
                        <td class="px-6 py-4">02-06-2025<br><span class="text-xs">14:32</span></td>
                        <td class="px-6 py-4">24 records</td>
                        <td class="px-6 py-4 text-green-600">✔️ Succesvol</td>
                    </tr>
                    <tr class="border-b hover:bg-hover">
                        <td class="px-6 py-4">
                            <div class="font-medium text-blue-600">aanwezigheid_week7_2025.xlsx</div>
                            <div class="text-xs text-gray-500">Week 7, 2025</div>
                        </td>
                        <td class="px-6 py-4">25-05-2025<br><span class="text-xs">09:15</span></td>
                        <td class="px-6 py-4">23 records</td>
                        <td class="px-6 py-4 text-yellow-600">⚠️ Met waarschuwingen</td>
                    </tr>
                    <tr class="border-b hover:bg-hover">
                        <td class="px-6 py-4">
                            <div class="font-medium text-blue-600">aanwezigheid_week6_2025.xlsx</div>
                            <div class="text-xs text-gray-500">Week 6, 2025</div>
                        </td>
                        <td class="px-6 py-4">18-05-2025<br><span class="text-xs">16:45</span></td>
                        <td class="px-6 py-4">0 records</td>
                        <td class="px-6 py-4 text-red-600">❌ Fout</td>
                    </tr>
                    <tr class="border-b hover:bg-hover">
                        <td class="px-6 py-4">
                            <div class="font-medium text-blue-600">aanwezigheid_week6_2025.xlsx</div>
                            <div class="text-xs text-gray-500">Week 6, 2025</div>
                        </td>
                        <td class="px-6 py-4">18-05-2025<br><span class="text-xs">16:45</span></td>
                        <td class="px-6 py-4">0 records</td>
                        <td class="px-6 py-4 text-red-600">❌ Fout</td>
                    </tr>
                    <tr class="border-b hover:bg-hover">
                        <td class="px-6 py-4">
                            <div class="font-medium text-blue-600">aanwezigheid_week6_2025.xlsx</div>
                            <div class="text-xs text-gray-500">Week 6, 2025</div>
                        </td>
                        <td class="px-6 py-4">18-05-2025<br><span class="text-xs">16:45</span></td>
                        <td class="px-6 py-4">0 records</td>
                        <td class="px-6 py-4 text-red-600">❌ Fout</td>
                    </tr>
                    <tr class="border-b hover:bg-hover">
                        <td class="px-6 py-4">
                            <div class="font-medium text-blue-600">aanwezigheid_week6_2025.xlsx</div>
                            <div class="text-xs text-gray-500">Week 6, 2025</div>
                        </td>
                        <td class="px-6 py-4">18-05-2025<br><span class="text-xs">16:45</span></td>
                        <td class="px-6 py-4">0 records</td>
                        <td class="px-6 py-4 text-red-600">❌ Fout</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="bg-blue-300 mt-5 text-blue-500 p-5 rounded  text-base/8">
            <h3>
                💡 Import tips:
            </h3>
            <li>Eerste rij moet kolomname bevatten</li>
            <li>Meerdere bestanden voor dezelfde week? Laatste bestand is leidend</li>
            <li>Nieuwe student worden automatisch ingezet</li>
        </div>
    </div>
</div>

@endsection