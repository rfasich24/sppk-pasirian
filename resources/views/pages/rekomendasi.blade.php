@extends('layouts.app')

@section('title', 'Pencarian & Rekomendasi Sekolah')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

        <div class="mb-10 border-b border-gray-200 pb-6 text-center md:text-left">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-3">
                ✨ Mode Publik (Tanpa Login)
            </span>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight sm:text-4xl">
                Pencarian Sekolah Menengah Pertama
            </h1>
            <p class="text-base text-gray-500 mt-2 max-w-2xl">
                Sistem Pendukung Keputusan berbasis metode <span class="font-semibold text-gray-800">AHP + SMART</span>.
                Pilih aspek sekolah yang paling Anda prioritaskan untuk mendapatkan rekomendasi terbaik di Kecamatan
                Pasirian.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden sticky top-6">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-800">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <span>🎛️</span> Atur Preferensi Anda
                    </h3>
                </div>

                <div class="p-6">
                    <form action="{{ route('smart.rekomendasi') }}" method="POST">
                        @csrf

                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">
                            Prioritas Aspek Sekolah:
                        </label>

                        <div class="space-y-3">
                            @foreach ($kriteriaAll as $k)
                                <label
                                    class="group flex items-center justify-between p-3.5 rounded-xl border border-gray-200 hover:border-green-500 hover:bg-green-50/30 cursor-pointer transition-all select-none shadow-sm bg-white">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" name="kriteria_dipilih[]" value="{{ $k->id }}"
                                            class="w-5 h-5 text-green-600 border-gray-300 rounded-lg focus:ring-green-500 focus:ring-2 transition cursor-pointer"
                                            {{ in_array($k->id, $kriteriaTerpilihIds) ? 'checked' : '' }}>
                                        <span
                                            class="font-semibold text-gray-700 group-hover:text-gray-900 transition text-sm">
                                            {{ $k->nama_kriteria }}
                                        </span>
                                    </div>
                                    <span
                                        class="text-xxs font-bold uppercase tracking-wide px-2 py-1 rounded-md shadow-sm border {{ $k->tipe == 'benefit' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-orange-50 text-orange-700 border-orange-200' }}">
                                        {{ $k->tipe }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <label for="limit"
                                class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">
                                Jumlah Hasil Rekomendasi:
                            </label>
                            <select name="limit" id="limit"
                                class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-sm focus:ring-green-500 focus:border-green-500 font-bold text-gray-700 cursor-pointer shadow-inner">
                                <option value="3" {{ $limit == 3 ? 'selected' : '' }}>🏆 Tampilkan 3 Terbaik</option>
                                <option value="5" {{ $limit == 5 ? 'selected' : '' }}>⚡ Tampilkan 5 Terbaik</option>
                                <option value="10" {{ $limit == 10 ? 'selected' : '' }}>📊 Tampilkan Semua</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md hover:shadow-lg transform active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <span>🔍</span> Analisis Rekomendasi
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-gray-900 font-bold text-lg flex items-center gap-2">
                        <span>🎯</span> Hasil Pemeringkatan Sekolah
                    </h3>
                    @if (!empty($hasilRekomendasi))
                        <span class="text-xs bg-gray-200 text-gray-700 px-2.5 py-1 rounded-md font-mono font-bold">
                            Count: {{ count($hasilRekomendasi) }}
                        </span>
                    @endif
                </div>

                <div class="p-6">
                    @if (empty($hasilRekomendasi))
                        <div class="text-center py-20 px-4">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-inner animate-bounce">
                                💡
                            </div>
                            <h4 class="text-lg font-bold text-gray-800">Menunggu Preferensi Anda</h4>
                            <p class="text-gray-400 text-sm max-w-sm mx-auto mt-1">
                                Silakan pilih kriteria sekolah ideal Anda di panel sebelah kiri, kemudian tekan tombol
                                "Analisis Rekomendasi".
                            </p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @php $rank = 1; @endphp
                            @foreach ($hasilRekomendasi as $hr)
                                <div
                                    class="group flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 border border-gray-200 rounded-2xl hover:border-blue-500 hover:shadow-md transition-all bg-white relative overflow-hidden">

                                    @if ($rank === 1)
                                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-yellow-400"></div>
                                    @endif

                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-10 h-10 rounded-xl font-black flex items-center justify-center text-base shadow-sm shrink-0 border
                                    {{ $rank === 1 ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : '' }}
                                    {{ $rank === 2 ? 'bg-gray-100 text-gray-700 border-gray-300' : '' }}
                                    {{ $rank === 3 ? 'bg-orange-100 text-orange-700 border-orange-300' : '' }}
                                    {{ $rank > 3 ? 'bg-gray-50 text-gray-500 border-gray-200' : '' }}">
                                            {{ $rank++ }}
                                        </div>

                                        <div>
                                            <h4
                                                class="font-extrabold text-gray-900 text-base group-hover:text-blue-600 transition">
                                                {{ $hr['nama_sekolah'] }}
                                            </h4>
                                            <div class="flex items-center space-x-2 mt-0.5">
                                                <span class="text-xs text-gray-400 font-medium">Kecamatan Pasirian</span>
                                                @if ($rank - 1 === 1)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xxs font-bold bg-yellow-100 text-yellow-800">⭐
                                                        Rekomendasi Utama</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="text-left sm:text-right mt-4 sm:mt-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100 w-full sm:w-auto flex sm:flex-col justify-between items-center sm:items-end">
                                        <span class="text-2xl font-mono font-black text-blue-600 tracking-tight">
                                            {{ number_format($hr['skor_akhir'], 4) }}
                                        </span>
                                        <p class="text-xxs text-gray-400 uppercase tracking-wider font-bold">
                                            Skor Akhir SMART
                                        </p>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
