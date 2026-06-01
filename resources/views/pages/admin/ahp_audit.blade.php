@extends('layouts.app')

@section('title', 'AHP Audit Trail - Admin Panel')

@section('content')
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4 space-y-6">

        <!-- Header Modul -->
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i data-lucide="sliders" class="w-5.5 h-5.5 text-emerald-700"></i>
                AHP Mathematical Audit Trail & Peer Consistency
            </h2>
            <p class="text-slate-500 text-xs mt-1">
                Lembar bedah transparansi algoritma perbandingan berpasangan kolektif (Geometric Mean) dari para responden
                pakar.
            </p>
        </div>

        <!-- Widgets Status Konsistensi -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nilai
                    $\lambda_{max}$</span>
                <span class="text-xl font-black font-mono text-slate-800">{{ number_format($lambda_max, 4) }}</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Consistency Index
                    (CI)</span>
                <span class="text-xl font-black font-mono text-slate-800">{{ number_format($ci, 4) }}</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Random Index (RI)</span>
                <span class="text-xl font-black font-mono text-slate-800">{{ $ri }}</span>
            </div>
            <div
                class="p-4 rounded-xl border shadow-2xs flex items-center justify-between {{ $is_konsisten ? 'bg-emerald-50/40 border-emerald-500/30' : 'bg-red-50 border-red-200' }}">
                <div>
                    <span
                        class="text-[10px] font-bold uppercase tracking-wider block {{ $is_konsisten ? 'text-emerald-700' : 'text-red-700' }}">Consistency
                        Ratio (CR)</span>
                    <span
                        class="text-xl font-black font-mono {{ $is_konsisten ? 'text-emerald-800' : 'text-red-800' }}">{{ number_format($cr, 4) }}</span>
                </div>
                <span
                    class="text-xxs font-black px-2 py-1 rounded-md uppercase tracking-wide {{ $is_konsisten ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white' }}">
                    {{ $is_konsisten ? 'Valid (<0.1)' : 'Invalid' }}
                </span>
            </div>
        </div>

        <!-- BARU: SELEKTOR & TABEL MATRIKS SAATY INDIVIDU RESPONDEN -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden space-y-4 p-6">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center border">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Inspeksi Matriks Saaty Per Responden</h3>
                        <p class="text-slate-400 text-xxs">Pilih nama pakar di sebelah kanan untuk melihat pembobotan murni
                            mereka.</p>
                    </div>
                </div>
                <div>
                    <select id="select-audit-user" onchange="switchAuditUser(this.value)"
                        class="bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-xs font-bold text-slate-700 cursor-pointer focus:outline-none focus:ring-1 focus:ring-emerald-500/30">
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ $selectedUserId == $u->id ? 'selected' : '' }}>
                                {{ $u->nama_lengkap }} ({{ $u->status_responden }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 text-slate-400 text-[10px] font-bold border-b border-slate-200/60">
                            <th class="p-2.5 text-left w-32">INDIVIDU PER PEER</th>
                            @foreach ($kriteria as $k)
                                <th class="p-2.5 font-mono font-bold text-slate-600">{{ $k->kode_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium text-xs">
                        @foreach ($kriteria as $row)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td
                                    class="p-2.5 text-left font-bold bg-slate-50/30 border-r border-slate-100 text-slate-900">
                                    {{ $row->nama_kriteria }}</td>
                                @foreach ($kriteria as $col)
                                    @php $valIndiv = $individualMatrix[$row->id][$col->id]; @endphp
                                    <td
                                        class="p-2.5 font-mono font-semibold {{ $row->id == $col->id ? 'text-slate-400 bg-slate-50/20' : ($valIndiv < 1.0 ? 'text-orange-600/80' : 'text-blue-600/80') }}">
                                        {{ number_format($valIndiv, $valIndiv < 1.0 ? 4 : 1) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Matriks Perbandingan Berpasangan Kolektif -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200/60 font-bold text-xs text-slate-600 tracking-wide">
                1. COMBINED PAIRWISE COMPARISON MATRIX (GEOMETRIC MEAN)
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-center text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-bold border-b border-slate-200/60">
                            <th class="p-3 text-left w-32">KRITERIA</th>
                            @foreach ($kriteria as $k)
                                <th class="p-3 font-mono font-bold">{{ $k->kode_kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @foreach ($kriteria as $row)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-3 text-left font-bold bg-slate-50/50 border-r border-slate-100">
                                    {{ $row->nama_kriteria }}</td>
                                @foreach ($kriteria as $col)
                                    <td class="p-3 font-mono text-xs">
                                        {{ number_format($matriks_kolektif[$row->id][$col->id], 4) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="bg-blue-50/30 text-blue-900 font-bold border-t border-blue-100">
                            <td class="p-3 text-left border-r border-blue-50">Total Kolom</td>
                            @foreach ($kriteria as $col)
                                <td class="p-3 font-mono text-xs">{{ number_format($total_kolom[$col->id], 4) }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Matriks Normalisasi & Prioritas Akhir -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200/60 font-bold text-xs text-slate-600 tracking-wide">
                2. NORMALIZED ROW MATRIX & FINAL CRITERIA WEIGHTS (EIGENVECTOR)
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-center text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-bold border-b border-slate-200/60">
                            <th class="p-3 text-left w-32">KRITERIA</th>
                            @foreach ($kriteria as $k)
                                <th class="p-3 font-mono">{{ $k->kode_kriteria }}</th>
                            @endforeach
                            <th class="p-3 bg-emerald-600 text-white font-bold rounded-tl-lg">EIGENVECTOR (BOBOT)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @foreach ($kriteria as $row)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-3 text-left font-bold bg-slate-50/50 border-r border-slate-100">
                                    {{ $row->nama_kriteria }}</td>
                                @foreach ($kriteria as $col)
                                    <td class="p-3 font-mono text-xs text-slate-400">
                                        {{ number_format($matriks_normalisasi[$row->id][$col->id], 4) }}</td>
                                @endforeach
                                <td
                                    class="p-3 font-mono text-sm bg-emerald-50/40 text-emerald-700 font-black border-l border-emerald-100">
                                    {{ number_format($bobot_kriteria[$row->id], 6) }}
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

        // Fungsi pengalihan URL dinamis untuk memuat ulang data pakar terpilih
        function switchAuditUser(userId) {
            window.location.href = "{{ route('admin.audit.ahp') }}?user_id=" + userId;
        }
    </script>
@endsection
