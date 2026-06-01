@extends('layouts.app')

@section('title', 'Validasi Visual Data SPPK')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Validasi Visual Data SPPK Pasirian</h1>
            <p class="text-gray-500 mt-2">Halaman pemeriksaan kecocokan data relasional pasca-seeding.</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">1. Master Data Kriteria</h2>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-800 text-white text-sm">
                            <th class="p-3 rounded-l">ID</th>
                            <th class="p-3">Kode</th>
                            <th class="p-3">Nama Kriteria</th>
                            <th class="p-3">Tipe (SMART)</th>
                            <th class="p-3 rounded-r">Bobot Global (AHP)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($kriteria as $k)
                            <tr class="hover:bg-gray-50 text-sm text-gray-700">
                                <td class="p-3 font-semibold">{{ $k->id }}</td>
                                <td class="p-3"><span
                                        class="bg-gray-200 px-2 py-1 rounded text-xs font-mono font-bold">{{ $k->kode_kriteria }}</span>
                                </td>
                                <td class="p-3 font-medium text-gray-900">{{ $k->nama_kriteria }}</td>
                                <td class="p-3">
                                    <span
                                        class="px-2 py-1 rounded text-xs font-bold text-white {{ $k->tipe == 'benefit' ? 'bg-green-600' : 'bg-red-600' }}">
                                        {{ strtoupper($k->tipe) }}
                                    </span>
                                </td>
                                <td class="p-3 font-mono text-blue-600 font-semibold">
                                    {{ $k->bobot_global ?? 'Belum Dihitung' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-green-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">2. Matriks Sekolah & Skor Parameter (SMART Awal)</h2>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-center border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="p-3 text-left rounded-l">Nama Sekolah</th>
                            <th class="p-3">Jarak (Cost)</th>
                            <th class="p-3">Fasilitas (Benefit)</th>
                            <th class="p-3">Biaya (Cost)</th>
                            <th class="p-3">Akreditasi (Benefit)</th>
                            <th class="p-3">Prestasi (Benefit)</th>
                            <th class="p-3 rounded-r">Ekstrakurikuler (Benefit)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700 font-medium">
                        @foreach ($sekolahData as $s)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 text-left font-bold text-gray-900 border-r">{{ $s->nama_sekolah }}</td>
                                <td class="p-3 text-green-600 font-mono">{{ number_format($s->jarak, 2) }}</td>
                                <td class="p-3 text-green-600 font-mono">{{ number_format($s->fasilitas, 2) }}</td>
                                <td class="p-3 text-green-600 font-mono">{{ number_format($s->biaya, 2) }}</td>
                                <td class="p-3 text-green-600 font-mono">{{ number_format($s->akreditasi, 2) }}</td>
                                <td class="p-3 text-green-600 font-mono">{{ number_format($s->prestasi, 2) }}</td>
                                <td class="p-3 text-green-600 font-mono">{{ number_format($s->ekstrakurikuler, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gray-800 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">3. Data Responden & Integritas Kuesioner</h2>
            </div>
            <div class="p-6">
                <table class="w-full text-left border-collapse text-sm mb-4">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 font-bold">
                            <th class="p-3">ID User</th>
                            <th class="p-3">Nama Lengkap</th>
                            <th class="p-3">Status / Jabatan</th>
                            <th class="p-3">Jumlah Isian Kuesioner (Upper Triangle)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-600">
                        @foreach ($users as $u)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-mono">{{ $u->id }}</td>
                                <td class="p-3 font-semibold text-gray-900">{{ $u->nama_lengkap }}</td>
                                <td class="p-3">{{ $u->status_responden }}</td>
                                <td class="p-3">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold text-white {{ $u->total_jawaban == 15 ? 'bg-green-600' : 'bg-yellow-500' }}">
                                        {{ $u->total_jawaban }} / 15 Pasang Kolom
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 text-blue-800 text-xs rounded-r shadow-inner">
                    💡 <strong>Info Teori AHP:</strong> Jumlah kombinasi perbandingan berpasangan untuk 6 kriteria adalah
                    $\frac{6(6-1)}{2} = 15$ baris kuesioner. Indikator hijau menandakan data responden utuh.
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('ahp.matriks') }}"
                class="inline-block bg-gray-700 hover:bg-gray-800 text-white font-medium px-6 py-2.5 rounded shadow transition">Lihat
                Analisis Matriks Saaty &rarr;</a>
        </div>
    </div>
@endsection
