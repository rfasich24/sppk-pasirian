@extends('layouts.app')

@section('title', 'Manajemen Sekolah - SPPK Pasirian')

@section('content')
    <script src="https://unpkg.com/lucide@latest"></script>

    @php
        // Cetak biru deskripsi opsi drop-down dari format data React Anda
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

    <div id="school-management-tab" class="max-w-7xl mx-auto px-6 lg:px-8 py-4 space-y-6">

        @if (session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl shadow-xs">
                🎉 {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <i data-lucide="school" class="w-5.5 h-5.5 text-emerald-700"></i>
                    Manajemen Sekolah & Parameter Kriteria
                </h2>
                <p class="text-slate-500 text-xs mt-1">
                    Kelola instansi SMP terdaftar di Pasirian beserta skor alternatif riil untuk kriteria keputusan.
                </p>
            </div>
            <button type="button" onclick="openAddModal()"
                class="bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs rounded-lg py-2.5 px-4 flex items-center gap-1.5 transition-all cursor-pointer shadow-sm shadow-emerald-700/5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Sekolah</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200/60 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                            <th class="py-3 px-5 w-16">No.</th>
                            <th class="py-3 px-5 min-w-[200px]">Nama Sekolah SMP</th>
                            @foreach ($criteriaList as $c)
                                <th class="py-3 px-4 text-center group relative">
                                    <span class="hover:text-emerald-700 cursor-help border-b border-dotted border-slate-400"
                                        title="{{ $c->nama_kriteria }}">
                                        {{ $c->kode_kriteria }}
                                    </span>
                                </th>
                            @endforeach
                            <th class="py-3 px-5 text-right w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @if ($schools->count() > 0)
                            @foreach ($schools as $index => $sch)
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="py-3 px-5 font-mono text-slate-400 font-medium">0{{ $index + 1 }}</td>
                                    <td class="py-3 px-5 font-semibold text-slate-900">{{ $sch->nama_sekolah }}</td>

                                    @foreach ($criteriaList as $c)
                                        @php
                                            $score = $ratings[$sch->id][$c->id] ?? 1;
                                            $mapping = $criteria_mappings[$c->kode_kriteria] ?? null;
                                            $optFound = $mapping
                                                ? collect($mapping['options'])->firstWhere('score', $score)
                                                : null;
                                            $titleHover = $optFound ? $optFound['text'] : 'Nilai ' . $score;
                                        @endphp
                                        <td class="py-3 px-4 text-center">
                                            <span
                                                class="inline-flex flex-col items-center justify-center font-mono text-xs font-bold px-2 py-1 rounded bg-slate-50 text-slate-800 border border-slate-200/60 cursor-help hover:bg-emerald-50/50"
                                                title="{{ $c->nama_kriteria }}: {{ $titleHover }}">
                                                {{ $score }}
                                            </span>
                                        </td>
                                    @endforeach

                                    <td class="py-3 px-5 text-right">
                                        <div class="flex gap-2 justify-end">
                                            <button type="button"
                                                onclick="openEditModal({{ $sch->id }}, '{{ addslashes($sch->nama_sekolah) }}', {{ json_encode($ratings[$sch->id] ?? []) }})"
                                                class="p-1.5 text-slate-600 hover:text-emerald-700 hover:bg-slate-100 rounded transition-colors cursor-pointer"
                                                title="Edit Sekolah">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </button>

                                            <form action="{{ route('admin.sekolah.destroy', $sch->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus &quot;{{ $sch->nama_sekolah }}&quot; beserta seluruh data parameternya?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-slate-600 hover:text-red-600 hover:bg-slate-100 rounded transition-colors cursor-pointer"
                                                    title="Hapus Sekolah">
                                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colSpan="{{ $criteriaList->count() + 3 }}"
                                    class="py-12 text-center text-slate-400 font-light">
                                    Belum ada sekolah terdaftar. Silakan tambahkan sekolah baru.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div id="crud-school-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs" onclick="closeCrudModal()"></div>

            <div
                class="relative bg-white w-full max-w-2xl rounded-2xl border border-slate-100 shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200/60 flex items-center justify-between">
                    <h3 id="modal-title-text" class="font-bold text-slate-900 text-base">
                        Tambah Sekolah SMP Baru
                    </h3>
                    <button type="button" onclick="closeCrudModal()"
                        class="p-1 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form id="modal-form-action" method="POST" class="p-6 space-y-6">
                    @csrf
                    <div id="method-append-box"></div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                            Nama Lengkap Sekolah SMP/MTs
                        </label>
                        <input id="form-school-name" name="nama_sekolah" type="text"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-3 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500/40 font-medium"
                            placeholder="Contoh: SMP Negeri 4 Pasirian" required />
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">
                            Parameter Penilaian (Descriptive Dropdowns)
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($criteriaList as $c)
                                @php $config = $criteria_mappings[$c->kode_kriteria] ?? null; @endphp
                                <div class="bg-slate-50 border border-slate-200/40 rounded-xl p-3.5 space-y-2">
                                    <div class="flex justify-between items-center">
                                        <label class="text-xs font-bold text-slate-800 flex items-center gap-1">
                                            {{ $c->nama_kriteria }}
                                            <span class="text-[10px] font-mono text-slate-400 uppercase">({
                                                $c->kode_kriteria })</span>
                                        </label>
                                        <span
                                            class="text-[9px] font-semibold tracking-wider font-mono px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 uppercase">
                                            {{ $c->tipe }}
                                        </span>
                                    </div>

                                    <select name="scores[{{ $c->id }}]" id="form-select-crit-{{ $c->id }}"
                                        class="w-full bg-white border border-slate-200 rounded-lg py-2 px-2.5 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-emerald-500/30 font-semibold cursor-pointer shadow-2xs">
                                        @if ($config)
                                            @foreach ($config['options'] as $opt)
                                                <option value="{{ $opt['score'] }}">{{ $opt['text'] }} (Skor:
                                                    {{ $opt['score'] }})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-3 justify-end pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeCrudModal()"
                            class="bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 font-medium text-xs rounded-lg px-4 py-2.5 transition-all cursor-pointer">
                            Batalkan
                        </button>
                        <button id="btn-save-school-crud" type="submit"
                            class="bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs rounded-lg px-5 py-2.5 flex items-center gap-1.5 transition-all shadow-sm shadow-emerald-700/10 cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span id="submit-button-text">Tambahkan Sekolah</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();

        const modal = document.getElementById('crud-school-modal');
        const form = document.getElementById('modal-form-action');
        const titleText = document.getElementById('modal-title-text');
        const submitBtnText = document.getElementById('submit-button-text');
        const schoolNameInput = document.getElementById('form-school-name');
        const methodAppend = document.getElementById('method-append-box');

        // Trigger Buka Modal Tambah Data
        function openAddModal() {
            titleText.innerText = "Tambah Sekolah SMP Baru";
            submitBtnText.innerText = "Tambahkan Sekolah";
            schoolNameInput.value = "";
            form.action = "{{ route('admin.sekolah.store') }}";
            methodAppend.innerHTML = ""; // Bersih tanpa PUT method

            // Reset dropdown ke skor terkecil default
            @foreach ($criteriaList as $c)
                document.getElementById('form-select-crit-{{ $c->id }}').selectedIndex = 0;
            @endforeach

            modal.classList.remove('hidden');
        }

        // Trigger Buka Modal Sunting / Edit Data (Mengisi value secara otomatis dari parameter baris)
        function openEditModal(id, schoolName, currentScores) {
            titleText.innerText = "Sunting Data: " + schoolName;
            submitBtnText.innerText = "Simpan Perubahan";
            schoolNameInput.value = schoolName;

            // Atur action form mengarah ke endpoint update laravel
            form.action = "/admin/sekolah/" + id;
            methodAppend.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Isi otomatis nilai dropdown berdasarkan parameter sekolah terpilih
            @foreach ($criteriaList as $c)
                if (currentScores && currentScores[{{ $c->id }}]) {
                    document.getElementById('form-select-crit-{{ $c->id }}').value = currentScores[
                        {{ $c->id }}];
                } else {
                    document.getElementById('form-select-crit-{{ $c->id }}').value = 1;
                }
            @endforeach

            modal.classList.remove('hidden');
        }

        function closeCrudModal() {
            modal.classList.add('hidden');
        }
    </script>
@endsection
