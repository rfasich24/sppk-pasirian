@extends('layouts.app')

@section('title', 'SMART Audit Trail - Admin Panel')

@section('content')
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 space-y-6">

        <!-- Header Modul -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i data-lucide="database" class="w-5.5 h-5.5 text-emerald-700"></i>
                SMART Utility Scaling & Alternate Parametrization
            </h2>
            <p class="text-slate-500 text-xs mt-1">
                Lembar bedah nilai utilitas transformasi parameter $u_k$ berdasarkan nilai batas maksimum dan minimum tiap
                kriteria alternatif.
            </p>
        </div>

        <!-- Tabel Matriks Matang Audit SMART -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200/60 font-bold text-xs text-slate-600 tracking-wide">
                GLOBAL ALTERNATIVE UTILITY MAPPING ($u_k$)
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-center text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-bold border-b border-slate-200/60">
                            <th class="py-3 px-5 text-left min-w-[220px]">NAMA INSTANSI SEKOLAH</th>
                            @foreach ($kriteriaList as $c)
                                <th class="py-3 px-4 font-bold">{{ $c->kode_kriteria }} (SKOR)</th>
                                <th class="py-3 px-4 text-emerald-600 font-bold bg-emerald-50/20">UTILITAS ($u)</th>
                            @endforeach
                            <th class="py-3 px-5 text-right font-black bg-slate-900 text-white rounded-tl-lg">SKOR AKHIR
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800 font-medium">
                        @foreach ($smartData['results'] as $res)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="py-3 px-5 text-left font-bold text-slate-900">{{ $res['schoolName'] }}</td>

                                @foreach ($kriteriaList as $c)
                                    @php
                                        $score = $res['rawScores'][$c->kode_kriteria] ?? 0;
                                        $util = $res['utilities'][$c->kode_kriteria] ?? 0;
                                    @endphp
                                    <td class="py-3 px-4 font-mono text-xs text-slate-500">{{ (int) $score }}</td>
                                    <td
                                        class="py-3 px-4 font-mono text-xs text-emerald-600 font-bold bg-emerald-50/10 border-r border-slate-100">
                                        {{ number_format($util, 2) }}
                                    </td>
                                @endforeach

                                <td
                                    class="py-3 px-5 text-right font-mono font-black text-blue-600 bg-slate-50/50 border-l border-slate-200">
                                    {{ number_format($res['finalScore'], 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <script>
        lucide.createIcons();
    </script>
@endsection
