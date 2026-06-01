@extends('layouts.app')

@section('title', 'Pencarian Sekolah Kustom Preferensi - SPPK Pasirian')

@section('content')
    <script src="https://unpkg.com/lucide@latest"></script>

    @php
        $criteria_mappings = [
            'K1' => [
                'options' => [
                    ['score' => 1, 'text' => 'Sangat Jauh'],
                    ['score' => 2, 'text' => 'Jauh'],
                    ['score' => 3, 'text' => 'Sedang'],
                    ['score' => 4, 'text' => 'Dekat'],
                    ['score' => 5, 'text' => 'Sangat Dekat'],
                ],
            ],
            'K2' => [
                'options' => [
                    ['score' => 1, 'text' => 'Sangat Kurang'],
                    ['score' => 2, 'text' => 'Kurang'],
                    ['score' => 3, 'text' => 'Cukup'],
                    ['score' => 4, 'text' => 'Lengkap'],
                    ['score' => 5, 'text' => 'Sangat Lengkap'],
                ],
            ],
            'K3' => [
                'options' => [
                    ['score' => 1, 'text' => 'Sangat Mahal'],
                    ['score' => 2, 'text' => 'Mahal'],
                    ['score' => 3, 'text' => 'Cukup'],
                    ['score' => 4, 'text' => 'Murah'],
                    ['score' => 5, 'text' => 'Sangat Murah'],
                ],
            ],
            'K4' => [
                'options' => [
                    ['score' => 1, 'text' => 'C (Cukup)'],
                    ['score' => 2, 'text' => 'B (Baik)'],
                    ['score' => 3, 'text' => 'A (Unggul)'],
                ],
            ],
            'K5' => [
                'options' => [
                    ['score' => 1, 'text' => 'Tidak Ada'],
                    ['score' => 2, 'text' => 'Kecamatan'],
                    ['score' => 3, 'text' => 'Kabupaten'],
                    ['score' => 4, 'text' => 'Provinsi'],
                    ['score' => 5, 'text' => 'Nasional'],
                ],
            ],
            'K6' => [
                'options' => [
                    ['score' => 1, 'text' => 'Sangat Sedikit'],
                    ['score' => 2, 'text' => 'Sedikit'],
                    ['score' => 3, 'text' => 'Cukup Banyak'],
                    ['score' => 4, 'text' => 'Banyak'],
                    ['score' => 5, 'text' => 'Sangat Banyak'],
                ],
            ],
        ];
    @endphp

    <div id="guest-panel" class="max-w-7xl mx-auto px-6 lg:px-8 py-8 space-y-10">

        <div class="text-center space-y-4 max-w-3xl mx-auto py-8">
            <div
                class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border border-emerald-100/90">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> PERSONALIZED RECOMMENDATION ENGINE
            </div>
            <h1 class="text-4xl font-extrabold sm:text-5xl tracking-tight text-slate-900 font-sans">
                Temukan SMP Terbaik di <span class="text-emerald-600">Pasirian</span>
            </h1>
            <p class="text-slate-500 text-base sm:text-lg leading-relaxed font-sans font-light">
                Sistem cerdas berbasis <strong class="font-semibold text-slate-800">AHP + SMART</strong>. Tentukan aspek
                prioritas beserta kriteria target sekolah idaman Anda secara langsung.
            </p>
        </div>

        <form action="{{ route('smart.rekomendasi') }}" method="POST"
            class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf

            <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200/85 shadow-sm p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 font-sans tracking-tight">
                        1. Atur Kriteria & Target Anda
                    </h2>
                    <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                        Centang aspek penting dan tentukan ekspektasi kondisi sekolah yang paling ideal bagi putra-putri
                        Anda di bawah ini.
                    </p>
                </div>

                <div class="space-y-4">
                    @foreach ($kriteriaAll as $c)
                        @php
                            $isSelected = in_array($c->id, $kriteriaTerpilihIds);
                            $config = $criteria_mappings[$c->kode_kriteria] ?? null;
                            $currentPrefVal = $userPreferences[$c->id] ?? 4;
                        @endphp
                        <div class="space-y-2">
                            <div id="card-crit-{{ $c->id }}" onclick="toggleCriteriaCheckbox({{ $c->id }})"
                                class="group relative flex items-center p-4 rounded-xl border text-left cursor-pointer transition-all duration-200 {{ $isSelected ? 'bg-emerald-50/30 border-emerald-500/65 shadow-2xs' : 'border-slate-200 hover:border-slate-300 bg-white' }}">

                                <input type="checkbox" name="kriteria_dipilih[]" value="{{ $c->id }}"
                                    id="input-crit-{{ $c->id }}" class="hidden" {{ $isSelected ? 'checked' : '' }}>

                                <div class="flex-1 flex gap-3.5 items-start">
                                    <div class="mt-1">
                                        <div id="box-crit-{{ $c->id }}"
                                            class="w-5 h-5 rounded border flex items-center justify-center transition-all {{ $isSelected ? 'bg-emerald-600 border-emerald-600 text-white shadow-xs' : 'border-slate-300 group-hover:border-slate-400 bg-white' }}">
                                            <i data-lucide="check" id="check-icon-{{ $c->id }}"
                                                class="w-3 h-3 stroke-[3] {{ $isSelected ? '' : 'hidden' }}"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-slate-800 text-sm group-hover:text-slate-950">
                                                {{ $c->nama_kriteria }}
                                            </span>
                                            <span
                                                class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 uppercase">
                                                {{ $c->kode_kriteria }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right pl-3 shrink-0">
                                    <div class="text-[9px] font-mono uppercase tracking-wider text-slate-400 font-semibold">
                                        Bobot Pakar</div>
                                    <div class="text-xs font-bold text-slate-700 font-mono">
                                        {{ number_format(($c->bobot_global ?? 0) * 100, 1) }}%
                                    </div>
                                </div>
                            </div>

                            <div id="target-wrapper-{{ $c->id }}"
                                class="{{ $isSelected ? '' : 'hidden' }} px-2 pl-8 animate-in fade-in duration-150">
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Target
                                    Kondisi Sekolah Yang Diharapkan:</label>
                                <select name="user_preferences[{{ $c->id }}]"
                                    id="dropdown-pref-{{ $c->id }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg py-1.5 px-2.5 text-xs text-slate-700 font-semibold focus:outline-none focus:ring-1 focus:ring-emerald-500/40 cursor-pointer">
                                    @if ($config)
                                        @foreach ($config['options'] as $opt)
                                            <option value="{{ $opt['score'] }}"
                                                {{ $currentPrefVal == $opt['score'] ? 'selected' : '' }}>
                                                🎯 {{ $opt['text'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-slate-200/80 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tampilkan
                                Hasil</label>
                            <select name="limit" id="select-limit-recomm"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-3 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500/40 font-medium cursor-pointer">
                                <option value="3" {{ $limit == '3' ? 'selected' : '' }}>3 Terbaik</option>
                                <option value="5" {{ $limit == '5' ? 'selected' : '' }}>5 Terbaik</option>
                                <option value="all" {{ $limit == 'all' ? 'selected' : '' }}>Tampilkan Semua</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" id="btn-cari-rekomendasi"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg py-2.5 px-4 flex items-center justify-center gap-2 transition-all cursor-pointer shadow-sm shadow-emerald-200">
                                <span>Cari Rekomendasi</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="lg:col-span-7 bg-white rounded-xl border border-slate-200/85 shadow-sm p-6 sm:p-8 space-y-6 min-h-[480px]">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 font-sans tracking-tight">
                            2. Peringkat Rekomendasi Personalisasi
                        </h2>
                        <p class="text-slate-500 text-xs mt-1">
                            Diurutkan berdasarkan tingkat kecocokan tertinggi terhadap kriteria kustom Anda.
                        </p>
                    </div>
                    @if ($recommData)
                        <span
                            class="text-xs font-bold bg-neutral-100 text-slate-700 px-3 py-1 rounded-full border border-slate-200">
                            {{ count($recommData['results']) }} Sekolah
                        </span>
                    @endif
                </div>

                @if ($recommData)
                    <div class="space-y-4">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/60 space-y-2">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-700 uppercase tracking-wide">
                                <i data-lucide="info" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                                Visualisasi Re-Normalisasi Bobot SMART
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 pt-2">
                                @foreach ($recommData['normalizedWeights'] as $code => $normW)
                                    @php $cInfo = $kriteriaAll->firstWhere('kode_kriteria', $code); @endphp
                                    <div class="bg-white p-2 rounded-lg border border-slate-200 text-center space-y-0.5">
                                        <div class="text-xs font-bold text-slate-800 font-mono">{{ $code }}</div>
                                        <div class="text-[9px] text-slate-400 font-medium truncate">
                                            {{ $cInfo->nama_kriteria ?? '' }}</div>
                                        <div class="text-xs font-bold font-mono text-emerald-600 mt-1">
                                            {{ number_format($normW * 100, 1) }}%
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach ($recommData['results'] as $item)
                                @php $isBest = $item['rank'] === 1; @endphp
                                <div
                                    class="group rounded-xl border transition-all duration-200 {{ $isBest ? 'border-emerald-500/40 bg-emerald-50/10 shadow-2xs' : 'border-slate-200 hover:border-slate-300 bg-white shadow-2xs' }}">
                                    <div class="p-4 sm:p-5 flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 sm:w-11 sm:h-11 rounded-full flex items-center justify-center font-bold font-mono text-sm {{ $item['rank'] === 1 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : ($item['rank'] === 2 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700') }}">
                                                {{ $item['rank'] }}
                                            </div>
                                            <div>
                                                <h3
                                                    class="font-bold text-slate-900 text-sm sm:text-base flex flex-wrap items-center gap-1.5 leading-tight">
                                                    {{ $item['schoolName'] }}
                                                    @if ($isBest)
                                                        <span
                                                            class="inline-flex items-center gap-1 text-[9px] bg-emerald-100 text-emerald-855 px-2 py-0.5 rounded-full font-bold uppercase tracking-wide">
                                                            <i data-lucide="star"
                                                                class="w-2.5 h-2.5 fill-current text-emerald-700"></i> Match
                                                            Sempurna
                                                        </span>
                                                    @endif
                                                </h3>
                                                <p
                                                    class="text-slate-400 text-[11px] mt-0.5 font-medium uppercase font-mono">
                                                    Lumajang, Jawa Timur • Pasirian District</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3">
                                            <div class="text-right">
                                                <span
                                                    class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Kesesuaian
                                                    Target</span>
                                                <span
                                                    class="text-lg sm:text-xl font-black text-slate-900 font-mono tracking-tight group-hover:text-emerald-650 transition-colors">
                                                    {{ number_format($item['finalScore'], 2) }}<span
                                                        class="text-xs font-bold text-slate-400 ml-0.5">%</span>
                                                </span>
                                            </div>
                                            <button type="button"
                                                onclick="toggleExplanationDrawer({{ $item['schoolId'] }})"
                                                class="p-1 px-2.5 rounded-lg border border-slate-200 text-slate-500 hover:text-slate-800 hover:border-slate-300 hover:bg-slate-50 text-xs flex items-center gap-1 transition-all cursor-pointer font-medium">
                                                <span>Detail</span>
                                                <i data-lucide="chevron-right" id="chevron-icon-{{ $item['schoolId'] }}"
                                                    class="w-3.5 h-3.5 transition-transform duration-200"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="drawer-expl-{{ $item['schoolId'] }}"
                                        class="hidden px-5 pb-5 pt-3 border-t border-slate-200/60 bg-slate-50/50 rounded-b-xl space-y-4">
                                        <div class="text-xs font-bold text-slate-800 uppercase tracking-wider font-mono">
                                            Detail Deviasi Parameter & Utilitas ($u_k$)
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                                    Kondisi Riil Sekolah</div>
                                                <div class="space-y-1.5">
                                                    @foreach ($kriteriaAll as $c)
                                                        @php
                                                            $rawScore = $item['rawScores'][$c->kode_kriteria] ?? 0;
                                                            $mapConf = $criteria_mappings[$c->kode_kriteria] ?? null;
                                                            $optFound = $mapConf
                                                                ? collect($mapConf['options'])->firstWhere(
                                                                    'score',
                                                                    (int) $rawScore,
                                                                )
                                                                : null;
                                                            $textShow = $optFound ? $optFound['text'] : $rawScore;
                                                        @endphp
                                                        <div
                                                            class="flex justify-between text-xs bg-white p-2 rounded border border-slate-200/50">
                                                            <span
                                                                class="font-semibold text-slate-700">{{ $c->nama_kriteria }}</span>
                                                            <span class="text-slate-600 font-semibold font-mono">
                                                                {{ $textShow }} ({{ (int) $rawScore }})
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                                    Tingkat Kecocokan Target ($u_k$)</div>
                                                <div class="space-y-1.5">
                                                    @foreach ($kriteriaAll as $c)
                                                        @php
                                                            $util = $item['utilities'][$c->kode_kriteria] ?? 0;
                                                            $isCritSel = in_array($c->id, $kriteriaTerpilihIds);
                                                        @endphp
                                                        <div
                                                            class="flex justify-between items-center text-xs p-2 rounded border {{ $isCritSel ? 'bg-emerald-50/35 border-emerald-500/20 shadow-2xs' : 'bg-white border-slate-200/60 opacity-60' }}">
                                                            <span
                                                                class="font-semibold text-slate-700">{{ $c->kode_kriteria }}
                                                                Target Match</span>
                                                            <div class="flex items-center gap-2">
                                                                <div
                                                                    class="w-16 bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                                                    <div class="bg-emerald-600 h-full"
                                                                        style="width: {{ $util * 100 }}%"></div>
                                                                </div>
                                                                <span
                                                                    class="font-bold text-slate-800 font-mono">{{ number_format($util * 100, 0) }}%</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-24 text-center text-slate-500 space-y-3">
                        <i data-lucide="school" class="w-12 h-12 stroke-[1.2] text-slate-300"></i>
                        <p class="text-sm font-light">Tidak ada data kalkulasi yang termuat.</p>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <script>
        lucide.createIcons();

        function toggleCriteriaCheckbox(id) {
            const checkbox = document.getElementById('input-crit-' + id);
            const card = document.getElementById('card-crit-' + id);
            const box = document.getElementById('box-crit-' + id);
            const icon = document.getElementById('check-icon-' + id);
            const wrapper = document.getElementById('target-wrapper-' + id);

            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                card.className =
                    "group relative flex items-center p-4 rounded-xl border text-left cursor-pointer transition-all duration-200 bg-emerald-50/30 border-emerald-500/65 shadow-2xs";
                box.className =
                    "w-5 h-5 rounded border flex items-center justify-center transition-all bg-emerald-600 border-emerald-600 text-white shadow-xs";
                icon.classList.remove('hidden');
                wrapper.classList.remove('hidden'); // Meluncurkan turun dropdown preferensi target
            } else {
                card.className =
                    "group relative flex items-center p-4 rounded-xl border text-left cursor-pointer transition-all duration-200 border-slate-200 hover:border-slate-300 bg-white";
                box.className =
                    "w-5 h-5 rounded border flex items-center justify-center transition-all border-slate-300 group-hover:border-slate-400 bg-white";
                icon.classList.add('hidden');
                wrapper.classList.add('hidden'); // Sembunyikan kembali dropdown target
            }
        }

        function toggleExplanationDrawer(schoolId) {
            const drawer = document.getElementById('drawer-expl-' + schoolId);
            const icon = document.getElementById('chevron-icon-' + schoolId);

            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                icon.classList.add('rotate-90');
            } else {
                drawer.classList.add('hidden');
                icon.classList.remove('rotate-90');
            }
        }
    </script>
@endsection
