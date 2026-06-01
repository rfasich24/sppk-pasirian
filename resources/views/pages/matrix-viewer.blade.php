@extends('layouts.app')

@section('title', 'Visualisasi Komputasi AHP')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">⚙️ Analisis & Komputasi Inti Metode AHP</h1>
            <p class="text-gray-500 mt-2">Rekonstruksi komputasi matriks Saaty gabungan dan tingkat konsistensi pakar.</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
                {{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gray-900 px-6 py-4">
                <h3 class="text-white font-semibold">1. Matriks Perbandingan Berpasangan Kolektif (Geometric Mean)</h3>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-center border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3 text-left">Kriteria</th>
                            @foreach ($kriteria as $k)
                                <th class="p-3 font-semibold">{{ $k->nama_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($kriteria as $row)
                            <tr>
                                <td class="p-3 text-left font-bold bg-gray-50 border-r">{{ $row->nama_kriteria }}</td>
                                @foreach ($kriteria as $col)
                                    <td class="p-3 font-mono text-gray-700">
                                        {{ number_format($matriks_kolektif[$row->id][$col->id], 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="bg-blue-50 font-bold text-blue-900">
                            <td class="p-3 text-left border-r">Total Kolom</td>
                            @foreach ($kriteria as $col)
                                <td class="p-3 font-mono">{{ number_format($total_kolom[$col->id], 4) }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h3 class="text-white font-semibold">2. Matriks Normalisasi & Prioritas (Eigenvector)</h3>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-center border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3 text-left">Kriteria</th>
                            @foreach ($kriteria as $k)
                                <th class="p-3 font-semibold">{{ $k->nama_kriteria }}</th>
                            @endforeach
                            <th class="p-3 bg-green-600 text-white rounded-tr">Bobot Kriteria (Eigenvector)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($kriteria as $row)
                            <tr>
                                <td class="p-3 text-left font-bold bg-gray-50 border-r">{{ $row->nama_kriteria }}</td>
                                @foreach ($kriteria as $col)
                                    <td class="p-3 font-mono text-gray-600">
                                        {{ number_format($matriks_normalisasi[$row->id][$col->id], 4) }}</td>
                                @endforeach
                                <td class="p-3 font-mono bg-green-50 text-green-700 font-bold border-l">
                                    {{ number_format($bobot_kriteria[$row->id], 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">4. Parameter Nilai Konsistensi Akhir</h4>
                <div class="space-y-2 text-sm font-medium text-gray-600">
                    <div class="flex justify-between"><span>Nilai $\lambda_{max}$</span> <span
                            class="font-mono font-bold text-gray-900">{{ number_format($lambda_max, 4) }}</span></div>
                    <div class="flex justify-between"><span>Consistency Index ($CI$)</span> <span
                            class="font-mono font-bold text-gray-900">{{ number_format($ci, 4) }}</span></div>
                    <div class="flex justify-between"><span>Index Random ($RI$ untuk $n=6$)</span> <span
                            class="font-mono font-bold text-gray-900">{{ $ri }}</span></div>
                    <div
                        class="flex justify-between p-2 bg-yellow-50 rounded text-yellow-900 border border-yellow-200 font-bold">
                        <span>Consistency Ratio ($CR$)</span> <span class="font-mono">{{ number_format($cr, 4) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-center text-center">
                <h5 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Status Validasi Konsistensi
                </h5>
                @if ($is_konsisten)
                    <div class="mb-4"><span
                            class="bg-green-100 text-green-800 font-bold px-4 py-2 rounded text-sm shadow-sm border border-green-300">KONSISTEN
                            ($CR < 0.1$)</span>
                    </div>
                    <form action="{{ route('ahp.simpan-bobot') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded shadow transition">💾
                            Simpan Bobot Global ke Database</button>
                    </form>
                @else
                    <div><span class="bg-red-100 text-red-800 font-bold px-4 py-2 rounded text-sm">TIDAK KONSISTEN ($CR \ge
                            0.1$)</span></div>
                    <p class="text-xs text-gray-400 mt-3">Silakan periksa kembali data isian kuesioner responden.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
